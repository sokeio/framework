<?php

namespace Sokeio\Console\Commands;

use Illuminate\Console\Command;
use Sokeio\Platform;

class FileCommand extends Command
{
    protected $signature = 'so:file {name} {fileType} {typeId}  {type=module}--cache-id';
    protected $description = 'This command is Create file in module or theme eg: php artisan so:file {name} {type} {moduleId or themeId} --cache-id';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $type = $this->argument('type');
        $typeId = $this->argument('typeId');
        $fileType = $this->argument('fileType');

        if ($type == 'theme') {
            //$typeId, $name, $fileType = 'page'
            Platform::theme()->generateFile($typeId, $name, $fileType);
        } else {
            Platform::module()->generateFile($typeId, $name, $fileType);
        }
        return 0;
    }
}
