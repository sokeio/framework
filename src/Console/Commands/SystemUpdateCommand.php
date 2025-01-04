<?php

namespace Sokeio\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Sokeio\Marketplate;

class SystemUpdateCommand extends Command
{
    protected $signature = 'so:system-update {secret?}';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $secret = $this->argument('secret');
        if ($secret) {
            Log::info('secret: ' . $secret);
        }

        Marketplate::updateNow(function ($msg) {
            $this->info($msg);
        }, $secret);

        return 0;
    }
}
