<?php

namespace Sokeio\Components\Field\Concerns;


trait WithSearchFn
{
    public function searchFn($searchFn): static
    {
        return $this->setKeyValue('searchFn', $searchFn);
    }
    public function getSearchFn()
    {
        return $this->getValue('searchFn');
    }
    public function querySearchFn($fnQuery, $name = null)
    {
        if (!$fnQuery || !is_callable($fnQuery)) {
            return $this;
        }
        if (!$name) {
            $name = 'searchFn_' . $this->getFieldText();
        }
        $this->searchFn($name);
        $this->actionUI($name, $fnQuery);
        $this->dataSource(function () use ($fnQuery) {
            return call_user_func($fnQuery, '');
        });
        return $this;
    }
}
