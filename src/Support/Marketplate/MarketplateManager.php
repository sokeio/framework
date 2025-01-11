<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Sokeio\Platform;
use Sokeio\Support\Marketplate\Provider\GithubProvider;
use Sokeio\Support\Marketplate\Provider\SokeioProvider;

class MarketplateManager
{
    private $marketplateUrl = 'https://sokeio.local/';
    private $product = null;
    private $marketplateProviders = [];
    public function registerProvider($key, $provider)
    {
        $this->marketplateProviders[$key] = $provider;
    }
    public function getProvider($key, $data)
    {
        if (!isset($this->marketplateProviders[$key]) || !class_exists($this->marketplateProviders[$key])) {
            return null;
        }
        return new ($this->marketplateProviders[$key])($data);
    }
    public function __construct()
    {
        // $this->marketplateUrl = config('sokeio.platform.marketplace');
        $this->registerProvider('github', GithubProvider::class);
        $this->registerProvider('sokeio', SokeioProvider::class);
    }
    private function makeRequest(string $url, array $data = []): \Illuminate\Http\Client\Response
    {
        $url = sprintf('%s%s', $this->getMarketplateUrl(), $url);
        return Http::withOptions(['verify' => false])->post($url, $data);
    }
    public function getProductInfo()
    {
        if ($this->product === null) {
            if (file_exists((config('sokeio.platform.product')))) {
                $this->product = json_decode(file_get_contents((config('sokeio.platform.product'))), true);
            } else {
                $this->product = [];
            }
        }
        return $this->product;
    }
    public function saveProductInfo()
    {
        $modules = [];
        foreach (Platform::module()->getAll() as $item) {
            $modules[$item->id] = $item->version ?? 'main';
        }
        $themes = [];
        foreach (Platform::theme()->getAll() as $item) {
            $themes[$item->id] = $item->version ?? 'main';
        };
        $product = $this->getProductInfo();
        if (empty($product['id'])) {
            $product = [
                'id' => 'sokeio/product-example',
                'name' => 'Sokeio Product Example',
                'description' => 'Sokeio Product Example',
                "framework" => "v1.0.0",
                'version' => 'v1.0.0',
                'author' => 'Sokeio',
                'url' => 'https://sokeio.com',
                'email' => 'contact@sokeio.com',
                'version' => 'v1.0.0',
                'modules' => $modules,
                'themes' => $themes,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        } else {
            $product['modules'] = array_merge($product['modules'], $modules);
            $product['themes'] = array_merge($product['themes'], $themes);
            $product['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->product = $product;
        file_put_contents((config('sokeio.platform.product')), json_encode($product, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    public function getMarketplateUrl()
    {
        return $this->marketplateUrl;
    }
    private function getInfoUpdater()
    {

        $this->saveProductInfo();
        $product = $this->getProductInfo();
        $modules = $product['modules'];
        $themes = $product['themes'];
        $productId = $product['id'];
        $productVersion = $product['version'];
        $framework = $product['framework'];
        return [
            'product_id' => $productId,
            'product_version' => $productVersion,
            'framework' => $framework,
            'modules' => $modules,
            'themes' => $themes,
        ];
    }
    public function getNewVersionInfo()
    {
        return $this->makeRequest('api/platform/product-info', $this->getInfoUpdater())->json();;
        // return  Cache::remember('marketplate_product_info', 60, function () {
        //     return $this->makeRequest('/product-info', $this->getInfoUpdater())->json();
        // });
    }
    public function checkNewVersion(): bool
    {
        $rs =  $this->getNewVersionInfo();
        return data_get($rs, 'is_updated') == true;
    }
    public function statusUpdate()
    {
        return json_decode(Cache::get(self::SYSTEM_UPDATE_CACHE_KEY), true);
    }
    private const SYSTEM_UPDATE_CACHE_KEY = 'marketplate_update';

    public function updateNow($callback = null, $secret = null): bool
    {
        $log = function ($msg) use ($callback) {
            if (is_array($msg)) {
                $msg = json_encode($msg);
            }
            Cache::set(self::SYSTEM_UPDATE_CACHE_KEY, $msg, 24 * 60 * 60);
            if ($callback) {
                $callback($msg);
            }
        };
        $log([
            'message' => 'start',
            'process' => 0,
        ]);

        $rs =  $this->getNewVersionInfo();
        // Backup => download => down => update => up
        if (data_get($rs, 'is_updated') == true) {
            $log([
                'message' => 'update',
                'process' => 0,
            ]);
            $progress = 0;
            $modules = collect(data_get($rs, 'modules', []))->map(function ($item, $key) {
                return new ItemUpdater($item, 'module');
            });
            $themes = collect(data_get($rs, 'themes', []))->map(function ($item, $key) {
                return new ItemUpdater($item, 'theme');
            });
            $totalProgress = (count($modules) + count($themes)) * 3;
            try {
                $this->backupAndDownload($modules, 'module', $progress, $totalProgress, $log);
                $this->backupAndDownload($themes, 'theme', $progress, $totalProgress, $log);
                // Run command system down
                Artisan::call('down', ['--secret' => $secret]);
                $this->itemUpdate($modules, 'module', $progress, $totalProgress, $log);
                $this->itemUpdate($themes, 'theme', $progress, $totalProgress, $log);
                Artisan::call('up');
            } catch (\Throwable $th) {
                $this->itemRollback($modules, 'module', $progress, $totalProgress, $log);
                $this->itemRollback($themes, 'theme', $progress, $totalProgress, $log);
                Cache::forget(self::SYSTEM_UPDATE_CACHE_KEY);
                return false;
            }
        }
        $log([
            'message' => 'done',
            'process' => 100,
        ]);
        Cache::forget(self::SYSTEM_UPDATE_CACHE_KEY);
        return true;
    }
    private function backupAndDownload($items, $type, &$progress, $totalProgress, $log)
    {
        foreach ($items as $item) {
            $progress++;
            $log([
                'message' => 'Backup ' . $type . ' : ' . $item->getName(),
                'process' => 100 * $progress / $totalProgress,
            ]);
            $item->backup();
            $progress++;
            $log([
                'message' => 'Download ' . $type . ' : ' . $item->getName(),
                'process' => 100 * $progress / $totalProgress,
            ]);
            $item->download();
        }
    }
    private function itemUpdate($items, $type, &$progress, $totalProgress, $log)
    {
        foreach ($items as $item) {
            $progress++;
            $log([
                'message' => 'Update ' . $type . ' : ' . $item->getName(),
                'process' => 100 * $progress / $totalProgress,
            ]);
            $item->update();
        }
    }
    private function itemRollback($items, $type, &$progress, $totalProgress, $log)
    {
        foreach ($items as $item) {
            $log([
                'message' => 'Rollback ' . $type . ' : ' . $item->getName(),
                'process' => 100,
            ]);
            try {
                $item->rollback();
            } catch (\Throwable $th) {
                $log([
                    'message' => $th->getMessage(),
                    'process' =>  100,
                ]);
            }
        }
    }
}
