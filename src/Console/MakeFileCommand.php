<?php

namespace Sokeio\Console;

use Illuminate\Console\Command;
use Sokeio\Concerns\WithGeneratorStub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFileCommand extends Command
{
    use WithGeneratorStub;
    protected $name = 'so:make-file';

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['base', 'b', InputOption::VALUE_OPTIONAL, 'Make by type', 'admin'],
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Make by type', 'module'],
            ['temp', 'te', InputOption::VALUE_OPTIONAL, 'template', 'controller'],
            ['list', 'l', InputOption::VALUE_OPTIONAL, 'Show template list', '']
        ];
    }
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names will be created.'],
        ];
    }
    private $fileName;
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $template = $this->option('temp');
        $this->components->info('Platform make by ' . $type . ':' . $template . '    ');
        $this->bootWithGeneratorStub();
        $names = $this->argument('name');
        $success = true;

        foreach ($names as $name) {
            $this->fileName = $name;
            $code = $this->GeneratorFileByStub($template);
            $this->components->info('module ' . $name . ' : ' .  $code);
            if ($code === E_ERROR) {
                $success = false;
            }
        }
        $this->info('done');

        return $success ? 0 : E_ERROR;
    }
}
