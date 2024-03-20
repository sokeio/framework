<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Breadcrumb;

trait WithModelQuery
{

    protected function getModel()
    {
        return null;
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery()
    {
        return ($this->getModel())::query();
    }
}
