<?php

namespace Sokeio\Console\Commands;

use Illuminate\Console\Command;
use Sokeio\Platform;

class MakeCommand extends Command
{
    protected $signature = 'so:make {name} {type=module} --cache-id';
    protected $description = 'This command is Create module or theme eg: php artisan so:make {name} {type} --cache-id';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $type = $this->argument('type');
        // $cacheId = $this->option('cache-id');
        if ($type == 'theme') {
            // create theme
            Platform::theme()->generate($name);
        } else {
            $type = 'module';
            Platform::module()->generate($name);
            // create module
        }

        $this->info("$name $type created successfully");

        return 0;
    }
}
