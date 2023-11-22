<?php

namespace Sokeio\Commands;

use Illuminate\Console\Command;
use Sokeio\Concerns\WithGeneratorStub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFileCommand extends Command
{
    use WithGeneratorStub;
    protected $name = 'mb:make-file';

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
    private $file_name;
    public function getFileName()
    {
        return $this->file_name;
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // foreach (Module::getAll() as $key => $item) {
        //     $this->components->info($key);
        //     $this->components->info($item->getPath());
        // }
        // return 0;
        $type = $this->option('type');
        $template = $this->option('temp');
        $this->components->info('Platform make by ' . $type . ':' . $template . '    ');
        $this->bootWithGeneratorStub();
        $names = $this->argument('name');
        $success = true;

        foreach ($names as $name) {
            $this->file_name = $name;
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
