<?php

namespace BytePlatform\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BActiveCommand extends Command
{
    protected $name = 'b:active';


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
        $byteplatform = byteplatform_by($type);
        $this->components->info('byte:' . $type);
        foreach ($names as $name) {
            $rs_byteplatform = $byteplatform->find($name);
            if ($rs_byteplatform) {
                if ($active === true) {
                    $rs_byteplatform->Active();
                    $this->components->info('module ' . $name . ' is Actived');
                } else {
                    $rs_byteplatform->UnActive();
                    $this->components->info('module ' . $name . ' is UnActived');
                }
            }
        }
        return 0;
    }
}
