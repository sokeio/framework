<?php

namespace Sokeio\UI\Table\Concerns;

use Sokeio\UI\Table\Table;

trait ColumnData
{
    public function enableLink(): static
    {
        return $this->vars('enableLink', true);
    }
    public function columnIndex($index): static
    {
        return $this->vars('columnIndex', $index);
    }
    public function getColumnIndex()
    {
        return $this->getVar('columnIndex', '', true);
    }
    private function fieldName($name): static
    {
        return $this->vars('name', $name);
    }
    public function attrHeader($key, $value): static
    {
        return $this->attr($key, $value, 'header');
    }
    public function label($label): static
    {
        return $this->vars('label', $label);
    }
    public function disableSort(): static
    {
        return $this->vars('disableSort', true);
    }
    
    public function classNameHeader($class): static
    {
        return $this->vars('classNameHeader', $class);
    }
    public function classNameCell($class): static
    {
        return $this->vars('classNameCell', $class);
    }
}
