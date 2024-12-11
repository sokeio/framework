<?php

namespace Sokeio\Console\Commands;

use Illuminate\Console\Command;
use Sokeio\Platform;

class DebugCommand extends Command
{
    protected $name = 'so:debug';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->comment('Hello, World!');
        // Platform::module()->setActive('sokeio/comment');
        // Platform::theme()->setActive('sokeio/theme-admin');
        $this->info(Platform::theme()->isActive('sokeio/theme-admin'));
        return 0;
    }
}
