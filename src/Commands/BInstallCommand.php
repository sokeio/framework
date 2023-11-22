<?php

namespace Sokeio\Commands;

use Illuminate\Console\Command;
use Sokeio\Facades\Platform;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BInstallCommand extends Command
{
    protected $name = 'b:install';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Recreate existing symbolic targets.', 'module'],
            ['active', 'a', InputOption::VALUE_OPTIONAL, 'Recreate existing symbolic targets.', true],
        ];
    }
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of modules will be actived.'],
        ];
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $active = $this->option('active');
        $names = $this->argument('name');
        $this->components->info('byte:' . $type);
        if (count($names)) {
            foreach ($names as $name) {
                $json =  Platform::install($name);
                if ($json) {
                    $this->components->info(json_encode($json));
                    if ($active == true)
                        run_cmd(base_path(), 'php artisan byte:active ' . $json['info']['id'] . ' -t ' . $json['type']);
                }
            }
        }
        return 0;
    }
}
