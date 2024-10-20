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
        $this->dataSource(function ($field) use ($fnQuery) {
            return call_user_func($fnQuery, $field->getManager(), '');
        });
        return $this;
    }
    public function querySearchWithModel($model, $name = null, $queryCallback = null, $optionNone = null)
    {
        $self = $this;
        return $this->querySearchFn(function ($component, $text, $currentId = null) use ($model, $self, $queryCallback, $optionNone) {
            $component->skipRender();
            $fieldText = $self->getFieldText();
            $query = ($model)::query()
                ->when($text != "", function ($query) use ($text, $fieldText) {
                    $query->where($fieldText, 'like', '%' . $text . '%');
                });
            if ($queryCallback) {
                $query = call_user_func($queryCallback, $query, $component, $text, $currentId);
            }
            $rs = $query->limit(20)->get(['id', $fieldText]);
            if ($currentId && $text == '') {
                $currentItem = ($model)::find($currentId);
                if ($currentItem) {
                    return [
                        ...($optionNone ? [
                            [
                                'id' => '',
                                $fieldText => $optionNone
                            ]
                        ] : []),
                        [
                            'id' => $currentItem->id,
                            $fieldText => $currentItem->name
                        ],
                        ...$rs->toArray(),
                    ];
                }
            }
            return $rs;
        }, $name);
    }
}
