<?php

namespace Sokeio\Commands;

use Illuminate\Console\Command;
use Sokeio\Facades\Updater;

class InstallCommand extends Command
{
    protected $name = 'so:install';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
    protected function getArguments()
    {
        return [];
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Updater::sokeioInstall();
        return 0;
    }
}
