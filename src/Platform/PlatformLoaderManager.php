<?php

namespace Sokeio\Platform;

use Illuminate\Pipeline\Pipeline;
use Sokeio\Platform\Loader\PageLoader;

class PlatformLoaderManager
{
    private $pipes = [
        PageLoader::class
    ];
    public function addPipe($pipe)
    {
        $this->pipes[] = $pipe;
        return $this;
    }
    public function apply($data)
    {
        return app(Pipeline::class)->send($data)
            ->through($this->pipes)->thenReturn();
    }
}
