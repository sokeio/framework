<?php

namespace Sokeio\Console;

use Illuminate\Console\Command;
use Sokeio\Platform;

class ModuleInstall extends Command
{
    protected $name = 'so:module:install';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Platform::module()->getMarketplate()->install('sokeio/content');
        return 0;
    }
}
