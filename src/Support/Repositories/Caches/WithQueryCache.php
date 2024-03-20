<?php

namespace Sokeio\Support\Repositories\Caches;

use Illuminate\Database\Eloquent\Model;

trait WithQueryCache
{

    /**
     * {@inheritDoc}
     */
    public function getModel()
    {
        return $this->repository->getModel();
    }

    /**
     * {@inheritDoc}
     */
    public function setModel(string $model)
    {
        return $this->repository->setModel($model);
    }

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return $this->repository->getTable();
    }

    /**
     * {@inheritDoc}
     */
    public function applyBeforeExecuteQuery($data, bool $isSingle = false)
    {
        return $this->repository->applyBeforeExecuteQuery($data, $isSingle);
    }

    /**
     * {@inheritDoc}
     */
    public function make(array $with = [])
    {
        return $this->repository->make($with);
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function findOrFail($id, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstBy(array $condition = [], array $select = [], array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function pluck(string $column, $key = null, array $condition = []): array
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function all(array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function allBy(array $condition, array $with = [], array $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function createOrUpdate($data, array $condition = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Model $model): bool
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function firstOrCreate(array $data, array $with = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $condition, array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function select(array $select = ['*'], array $condition = [])
    {
        return $this->getDataWithoutCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function deleteBy(array $condition = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function count(array $condition = []): int
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getByWhereIn($column, array $value = [], array $args = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function advancedGet(array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function forceDelete(array $condition = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function restoreBy(array $condition = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstByWithTrash(array $condition = [], array $select = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function insert(array $data): bool
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function firstOrNew(array $condition)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
