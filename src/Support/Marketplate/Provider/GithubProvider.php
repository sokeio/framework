<?php

namespace Sokeio\Support\Marketplate\Provider;

use Illuminate\Support\Facades\Http;
use Sokeio\Support\Marketplate\BaseProvider;

class GithubProvider extends BaseProvider
{
    public function downloadZip(): mixed
    {
        $url = $this->getDataValue('download_link');

        $headers = [];

        $response = Http::get($url, [
            'headers' => $headers,
        ]);
        if ($response->successful()) {
            return $response->body();
        }
        return false;
    }
}
