<?php

namespace Sokeio\Providers;

use Illuminate\Support\ServiceProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
        $this->registerSocialiteGithub();
    }
    private function registerSocialiteGithub()
    {
        // // if (
        // //     !setting('SOKEIO_GITHUB_CLIENT_ID')
        // //     || !setting('SOKEIO_GITHUB_CLIENT_SECRET')
        // //     || !setting('SOKEIO_GITHUB_CLIENT_REDIRECT')
        // //     || !setting('SOKEIO_GITHUB_ENABLE')
        // // ) {
        // //     return;
        // // }
        // dd([
        //     'services.github' => [
        //         'client_id' => setting('SOKEIO_GITHUB_CLIENT_ID'),
        //         'client_secret' => setting('SOKEIO_GITHUB_CLIENT_SECRET'),
        //         'redirect' => setting('SOKEIO_GITHUB_REDIRECT')
        //     ]
        // ]);
        config([
            'services.github' => [
                'client_id' => setting('SOKEIO_GITHUB_CLIENT_ID'),
                'client_secret' => setting('SOKEIO_GITHUB_CLIENT_SECRET'),
                'redirect' => setting('SOKEIO_GITHUB_REDIRECT')
            ]
        ]);
    }
}
