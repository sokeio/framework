<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Button;

class ButtonEditableAll extends BaseUI
{

    public function editText(string $text)
    {
        return $this->vars('editText', $text);
    }
    public function saveText(string $text)
    {
        return $this->vars('saveText', $text);
    }
    public function cancelText(string $text)
    {
        return $this->vars('cancelText', $text);
    }
    protected function initUI()
    {
        $this->register(function (self $base) {
            $base->setupChild(
                fn($c) =>
                $c->className('ms-1')
                // ->attr(
                //     'x-show',
                //     fn() =>
                //     '!checkRowEditline(\'' . $base->getParams('rowValueId') . '\')'
                // )
            );
            $base->child([
                Button::make()->label(fn() => $base->getVar('editText', __('Edit All')))
                    ->icon('ti ti-pencil')
                    ->className('btn btn-success')->editRowAllSelected(),
                Button::make()->label(fn() => $base->getVar('saveText', __('Save All')))
                    ->icon('ti ti-save')
                    ->className('btn btn-success')->SaveRowAllSelected(),
                Button::make()->label(fn() => $base->getVar('cancelText', __('Cancel All')))
                    ->icon('ti ti-close')
                    ->className('btn btn-danger')->CancelRowAllSelected(),
            ], 'default', true);
        });
    }
}
