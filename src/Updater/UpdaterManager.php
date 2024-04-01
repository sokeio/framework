<?php

namespace Sokeio;

use Illuminate\Support\Facades\Http;

class UpdaterManager
{
    private $domain = 'http://sokeio.local/api/marketplace/'; // 'https://api.sokeio.com';
    private $versionApi = 'v1';
    private $productId = 'sokeio';
    private $licenseKey = 'jx26dpclu8d7vfhyjgsnlhviezcan612';
    private \Illuminate\Http\Client\PendingRequest $client;
    private function initClient()
    {
        $this->client = Http::withHeaders([
            'X-License-Product' => $this->productId,
            'X-License-Key' => $this->licenseKey,
            'X-License-Domain' => config('app.url'),
            'Accept' => 'application/vnd.api+json',
        ])->baseUrl($this->domain .  $this->versionApi);
    }
    public function __construct()
    {
        $this->initClient();
    }

    public function getLastVersion($arrs = [], $type = 'module')
    {
        $response = $this->client->post($type . 's/latest', [
            'data' => $arrs
        ]);
        return $response->json();
    }
}
