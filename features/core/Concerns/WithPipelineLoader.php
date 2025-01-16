<?php

namespace Sokeio\Core\Concerns;

use Illuminate\Pipeline\Pipeline;
use Sokeio\Core\Loader\ControllerLoader;
use Sokeio\Core\Loader\PageLoader;
use Sokeio\Core\Loader\LivewireLoader;
use Sokeio\Core\Loader\ModelLoader;

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
