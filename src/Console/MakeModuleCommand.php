<?php

namespace Sokeio\Console;

use Symfony\Component\Console\Input\InputOption;

class MakeModuleCommand extends MakeCommand
{
    protected $name = 'so:make-module';
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['list', 'l', InputOption::VALUE_OPTIONAL, 'Show type list', ''],
            ['active', 'a', InputOption::VALUE_OPTIONAL, 'active.', false],
            ['force', 'f', InputOption::VALUE_OPTIONAL, 'force.', false],
        ];
    }
    public function getBaseTypeName()
    {
        return 'module';
    }
}
