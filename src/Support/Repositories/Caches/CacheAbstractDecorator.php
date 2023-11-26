<?php

namespace Sokeio\Support\Repositories\Caches;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Psr\SimpleCache\InvalidArgumentException;
use Sokeio\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Cache;

abstract class CacheAbstractDecorator implements RepositoryInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var String
     */
    protected $cacheGroup;

    /**
     * @var boolean
     */
    protected $cacheFlag = true;


    /**
     * CacheAbstractDecorator constructor.
     * @param RepositoryInterface $repository
     * @param string|null $cacheGroup
     */
    public function __construct(RepositoryInterface $repository, string $cacheGroup = null)
    {
        $this->repository = $repository;
        $this->cache = Cache::getFacadeRoot();
        $this->cacheGroup =  $cacheGroup ?? get_class($repository->getModel());
    }
    protected function getCacheKey($function, $args)
    {
        return md5($this->cacheGroup) . md5(
            get_class($this) .
                $function .
                serialize(request()->input()) . serialize(url()->current()) .
                serialize(json_encode($args))
        );
    }
    public function WithoutCache()
    {
        $this->cacheFlag = false;
        return $this;
    }
    /**
     * @param string $function
     * @param array $args
     * @return mixed
     */
    public function getDataIfExistCache(string $function, array $args)
    {
        if (!$this->cacheFlag || !setting('enable_cache', true) || (sokeio_is_admin() && setting('disable_cache_in_the_admin_panel', false))) {
            return $this->applyBeforeCache(call_user_func_array([$this->repository, $function], $args));
        }

        try {
            $cacheKey = $this->getCacheKey($function, $args);

            if ($this->cache->has($cacheKey)) {
                return  $this->applyBeforeCache($this->cache->get($cacheKey));
            }

            $cacheData = call_user_func_array([$this->repository, $function], $args);

            $this->cache->put($cacheKey, $cacheData);

            return  $this->applyBeforeCache($cacheData);
        } catch (Exception | InvalidArgumentException $ex) {
            info($ex->getMessage());

            return  $this->applyBeforeCache(call_user_func_array([$this->repository, $function], $args));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function applyBeforeCache($data)
    {
        $this->cacheFlag = true;
        return $data;
    }
    /**
     * @param string $function
     * @param array $args
     * @return mixed
     */
    public function getDataWithoutCache(string $function, array $args)
    {
        return call_user_func_array([$this->repository, $function], $args);
    }

    /**
     * @param string $function
     * @param array $args
     * @param boolean $flushCache
     * @return mixed
     */
    public function flushCacheAndUpdateData(string $function, array $args, bool $flushCache = true)
    {
        if ($flushCache) {
            $this->cache->flush();
        }

        return call_user_func_array([$this->repository, $function], $args);
    }

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
