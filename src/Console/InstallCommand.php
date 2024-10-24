<?php

namespace Sokeio\Console;

use Illuminate\Console\Command;
use Sokeio\Facades\Client;

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
        Client::savePackage();
        return 0;
    }
}
