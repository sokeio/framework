<?php

namespace Sokeio\Support\Repositories\Caches;

use Exception;
use Psr\SimpleCache\InvalidArgumentException;
use Sokeio\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Cache;

abstract class CacheAbstractDecorator implements RepositoryInterface
{
    use WithQueryCache;
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
    public function withoutCache()
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
        if (
            !$this->cacheFlag ||
            !setting('enable_cache', true) ||
            (sokeioIsAdmin() &&
                setting('disable_cache_in_the_admin_panel', false))
        ) {
            return $this->applyBeforeCache(call_user_func_array([$this->repository, $function], $args));
        }
        $rs = null;
        try {
            $cacheKey = $this->getCacheKey($function, $args);

            if ($this->cache->has($cacheKey)) {
                return  $this->applyBeforeCache($this->cache->get($cacheKey));
            }

            $cacheData = call_user_func_array([$this->repository, $function], $args);
            $this->cache->put($cacheKey, $cacheData);
            $rs =  $this->applyBeforeCache($cacheData);
        } catch (Exception | InvalidArgumentException $ex) {
            info($ex->getMessage());
            $rs =  $this->applyBeforeCache(call_user_func_array([$this->repository, $function], $args));
        }
        return $rs;
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

}
