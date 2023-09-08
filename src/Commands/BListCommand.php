<?php

namespace BytePlatform\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BListCommand extends Command
{
    protected $name = 'b:list';


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
        $byteplatform = platform_by($type);
        $this->components->info('Platform:' . $type);
        foreach ($byteplatform->getAll() as $item) {
            $this->components->info($item->name . ':' . ($item->isActive() ? 'Actived' : 'UnActived'));
        }
        return 0;
    }
}
