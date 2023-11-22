<?php

namespace Sokeio\Commands;

use Sokeio\Seeders\Init;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BSetupCommand extends Command
{
    protected $name = 'b:setup';


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
        $this->info('setup user');
        Artisan::call('db:seed', ['class' => Init::class]);
        $this->info('setup Link');
        Artisan::call('b:link', []);
        return 0;
    }
}
