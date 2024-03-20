<?php

namespace Sokeio\Support\Repositories\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sokeio\Support\Repositories\Interfaces\RepositoryInterface;

abstract class RepositoriesAbstract implements RepositoryInterface
{
    use WithQuery;
    /**
     * @var Eloquent | Model | SoftDeletes
     */
    protected $model;

    /**
     * @var Eloquent | Model | SoftDeletes
     */
    protected $originalModel;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->originalModel = $model;
    }

    /**
     * {@inheritDoc}
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return $this->model->getTable();
    }
}
