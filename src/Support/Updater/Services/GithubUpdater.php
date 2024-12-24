<?php

namespace Sokeio\Support\Updater\Services;

use Illuminate\Support\Facades\Http;
use Sokeio\Support\Updater\UpdaterService;

class GithubUpdater extends UpdaterService
{
    public function downloadZip(): mixed
    {
        $organization = $this->getDataValue('organization');
        $repository = $this->getDataValue('repository');
        $tag = $this->getDataValue('tag');
        $token = $this->getDataValue('token');
        $url = "https://api.github.com/repos/{$organization}/{$repository}/zipball/{$tag}";

        $headers = [
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'Sokeio',
        ];
        if ($token) {
            $headers['Authorization'] = "token {$token}";
        }
        $response = Http::get($url, [
            'headers' => $headers,
        ]);
        if ($response->successful()) {
            return $response->body();
        }
        return false;
    }
}
