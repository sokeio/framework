<?php

namespace BytePlatform\Traits;

use BytePlatform\BaseManager;

trait WithItemManager
{
    use WithHelpers;
    private  $cache__itemManager = null;
    private  $cache__query = null;
    protected function ItemManager(): BaseManager|null
    {
        return null;
    }
    public function getItemManager()
    {
        return ($this->cache__itemManager) ?? ($this->cache__itemManager = $this->ItemManager()?->CheckHook());
    }
    public function ClearCacheItemManager()
    {
        $this->cache__itemManager = null;
    }
    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->getItemManager()->getBeforeQuery($this->getItemManager()->getQuery());
    }

    public function newModel()
    {
        return new ($this->getItemManager()->getModel());
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return ($this->cache__query) ?? ($this->cache__query = $this->newQuery());
    }
    public function callDoAction($key, $params = [])
    {
        return $this->getItemManager()->callDoAction($key, $params, $this);
    }
}
