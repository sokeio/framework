<?php

namespace Sokeio\Admin\Components\Concerns;

use Sokeio\Breadcrumb;

trait WithLayoutUI
{
    public function getTitle()
    {
    }
    public function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    private $columns = [];
    public function addColumn($column)
    {
        $this->columns[] = $column;
        return $this;
    }
    /**
     * Get a new query builder for the model's table.
     *
     *
     * @return \Sokeio\Admin\Components\Field\BaseField[]
     */
    protected function getColumns()
    {
        return $this->columns;
    }
    protected function reLayout($layout)
    {
        if ($layout) {
            if (is_object($layout)) {
                $layout = [$layout];
            }
            foreach ($layout as $item) {
                if ($item) {
                    $item->Manager($this);
                    $item->boot();
                }
            }
        }
        return $layout;
    }
}
