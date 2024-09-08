<?php

namespace Sokeio\Support\Platform\Concerns;

use Illuminate\Pipeline\Pipeline;
use Sokeio\Support\Platform\Loader\PageLoader;
use Sokeio\Support\Platform\Loader\LivewireLoader;

trait WithPipelineLoader
{
    private $pipelineLoader = [
        PageLoader::class,
        LivewireLoader::class
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
