<?php

namespace Sokeio\Console\Commands;

use Illuminate\Console\Command;
use Sokeio\Marketplate;

class DebugCommand extends Command
{
    protected $signature = 'so:debug';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Marketplate::checkNewVersion();
        return 0;
    }
}
