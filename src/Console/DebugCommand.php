<?php

namespace Sokeio\Console;

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
        Platform::module()->setActive('sokeio/comment');
        return 0;
    }
}
