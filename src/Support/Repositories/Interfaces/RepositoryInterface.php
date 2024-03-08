<?php

namespace Sokeio\Support\Repositories\Interfaces;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

interface RepositoryInterface extends QueryInterface
{
    /**
     * @param Builder|Model $data
     * @param bool $isSingle
     * @return Builder
     */
    public function applyBeforeExecuteQuery($data, bool $isSingle = false);

    /**
     * Runtime override of the model.
     *
     * @param string $model
     * @return $this
     */
    public function setModel(string $model);

    /**
     * Get empty model.
     * @return Eloquent|Model|SoftDeletes
     */
    public function getModel();

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTable(): string;
    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     */
    public function make(array $with = []);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function advancedGet(array $params = []);
}
