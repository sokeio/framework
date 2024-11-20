<?php

namespace Sokeio\Support\Platform\Concerns;

use Illuminate\Pipeline\Pipeline;
use Sokeio\Support\Platform\Loader\ControllerLoader;
use Sokeio\Support\Platform\Loader\PageLoader;
use Sokeio\Support\Platform\Loader\LivewireLoader;
use Sokeio\Support\Platform\Loader\ModelLoader;

trait WithPipelineLoader
{
    private $pipelineLoader = [
        ControllerLoader::class,
        PageLoader::class,
        LivewireLoader::class,
        ModelLoader::class
    ];
    public function addLoader($loader)
    {
        $this->pipelineLoader[] = $loader;
    }
    public function applyLoader($data)
    {
        return app(Pipeline::class)->send($data)
            ->through($this->pipelineLoader)->thenReturn();
    }
}
