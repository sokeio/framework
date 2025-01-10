<?php

namespace Sokeio\Console\Commands;

use Illuminate\Console\Command;
use Sokeio\Marketplate;
use Sokeio\Platform;

class DebugCommand extends Command
{
    protected $signature = 'so:debug';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Marketplate::checkNewVersion();
        foreach (Platform::theme()->getAll() as $theme) {
            $this->info("Theme: {$theme->getId()} - {$theme->getPath()}");
        }
        return 0;
    }
}
