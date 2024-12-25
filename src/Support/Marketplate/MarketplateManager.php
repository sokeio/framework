<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sokeio\Platform;

class MarketplateManager
{
    private $marketplateUrl = 'https://sokeio.local/';
    private $product = null;
    public function __construct()
    {
        // $this->marketplateUrl = config('sokeio.platform.marketplace');
    }
    private function makeRequest(string $url, array $data = []): \Illuminate\Http\Client\Response
    {
        $url = sprintf('%s%s', $this->getMarketplateUrl(), $url);
        Log::info(['url' => $url, 'data' => $data]);
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
    public function getNewVersionInfo()
    {
        $this->saveProductInfo();
        $product = $this->getProductInfo();
        $modules = $product['modules'];
        $themes = $product['themes'];
        $productId = $product['id'];
        $productVersion = $product['version'];
        $framework = $product['framework'];
        $response = $this->makeRequest('/product-new-version', [
            'product_id' => $productId,
            'product_version' => $productVersion,
            'framework' => $framework,
            'modules' => $modules,
            'themes' => $themes,
        ]);

        return $response->json();
    }
    public function checkNewVersion(): bool
    {
        $response = $this->getNewVersionInfo();
        if (data_get($response, 'is_update') == 'success') {
            return true;
        }


        return false;
    }
}
