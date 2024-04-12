<?php

namespace Sokeio\Livewire\Role;

use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Models\Role;

class RoleTable extends Table
{
    protected function getModel(): string
    {
        return Role::class;
    }
    public function getTitle()
    {
        return __('Role');
    }
    protected function getRoute()
    {
        return 'admin.system.role';
    }

    public function doChangeStatus($id, $status)
    {
        $this->getQuery()->where('id', $id)->update(['status' => $status]);
    }
    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Name')),
            UI::text('slug')->label(__('Slug')),
            UI::button('status')->label(__('Status'))->NoSort()->wireClick(function ($item) {
                if ($item->getDataItem()->status) {
                    $item->title(__('Active'));
                    $item->primary();
                } else {
                    $item->title(__('Block'));
                    $item->warning();
                }
                $status = $item->getDataItem()->status ? 0 : 1;
                return 'doChangeStatus(' . $item->getDataItem()->id . ',' . $status . ')';
            })
        ];
    }
}
