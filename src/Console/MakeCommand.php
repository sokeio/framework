<?php

namespace Sokeio\Console;

use Illuminate\Console\Command;
use Sokeio\Concerns\WithGeneratorStub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeCommand extends Command
{
    use WithGeneratorStub;
    protected $name = 'so:make';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Make by type', 'module'],
            ['list', 'l', InputOption::VALUE_OPTIONAL, 'Show type list', ''],
            ['active', 'a', InputOption::VALUE_OPTIONAL, 'active.', false],
            ['force', 'f', InputOption::VALUE_OPTIONAL, 'force.', false],
        ];
    }
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of modules will be created.'],
        ];
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->getBaseTypeName();
        $active = $this->option('active');
        $this->force = $this->option('force');

        $this->components->info('Platform make by ' . $type);
        $this->bootWithGeneratorStub();
        $names = $this->argument('name');
        $success = true;

        foreach ($names as $name) {
            $code = $this->generate($name);
            $this->components->info('module ' . $name . ' : ' .  ($code == 0 ? 'OK' : 'ERROR'));
            if ($code == 0 && $active === true) {
                runCmd(base_path(''), 'php artisan so:active ' . $name . ' -t ' . $type);
            }
            if ($code === E_ERROR) {
                $success = false;
            }
        }
        $this->info('done');

        return $success ? 0 : E_ERROR;
    }
}
