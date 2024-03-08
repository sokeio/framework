<?php

namespace Sokeio\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends Command
{
    protected $name = 'so:list';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Recreate existing symbolic targets.', 'module'],
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $sokeio = platformBy($type);
        $this->components->info('Platform:' . $type);
        foreach ($sokeio->getAll() as $item) {
            $this->components->info($item->name . ':' . ($item->isActive() ? 'Actived' : 'blockd'));
        }
        return 0;
    }
}
