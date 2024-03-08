<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Facades\File;

trait WithPlatformTime
{
    private $startTime = 0;
    public function start()
    {
        $this->startTime = microtime(true);

        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            runCmd(base_path(''), 'php artisan key:generate');
            $path = public_path(platformPath());
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }
        }
    }
    public function executionTime()
    {
        $endTime = microtime(true);
        return round($endTime - $this->startTime, 4);
    }
}
