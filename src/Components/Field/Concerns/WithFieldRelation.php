<?php

namespace Sokeio\Components\Field\Concerns;


trait WithFieldRelation
{
    public function syncRelations($syncRelations = true): static
    {
        return $this->setKeyValue('syncRelations', $syncRelations);
    }
    public function getSyncRelations()
    {
        return $this->getValue('syncRelations');
    }
}
