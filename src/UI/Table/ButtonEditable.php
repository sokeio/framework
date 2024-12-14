<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Button;

class ButtonEditable extends BaseUI
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

                $c->attr(
                    'x-show',
                    fn() =>
                    '!checkRowEditline(\'' . $base->getParams('rowValueId') . '\')'
                )->className('ms-1')
            );
            $base->child([
                Button::make()
                    ->text(fn() => $base->getVar('editText', __('Edit')))
                    ->className('btn btn-primary btn-sm ')
                    ->wireKey(fn() => 'rowEditline-' . $base->getParams('rowValueId'))
                    ->attr('@click', fn() => 'tableRowEditline(\'' . $base->getParams('rowValueId') . '\')')
                    ->attr('x-show', fn() => '!checkRowEditline(\'' . $base->getParams('rowValueId') . '\')'),
                Button::make()
                    ->text(fn() => $base->getVar('saveText', __('Save')))
                    ->className('btn btn-success btn-sm me-1 ')
                    ->wireKey(fn() => 'rowSave-' . $base->getParams('rowValueId'))
                    ->attr('x-show', fn() => 'checkRowEditline(\'' . $base->getParams('rowValueId') . '\')')
                    // ->confirm(__('Are you sure want to save?'), 'Confirm')
                    ->wireClick('saveRowEditline', function (Button $button) {
                        return [$button->getParams('rowValueId')];
                    }),
                Button::make()
                    ->text(fn() => $base->getVar('cancelText', __('Cancel')))
                    ->className('btn btn-warning btn-sm ')
                    ->wireKey(fn() => 'rowCancel-' . $base->getParams('rowValueId'))
                    ->attr('x-show', fn() => 'checkRowEditline(\'' . $base->getParams('rowValueId') . '\')')
                    ->attr('@click', fn() => 'cancelRowEditline(\'' . $base->getParams('rowValueId') . '\')')
                // ->confirm(__('Are you sure want to cancel?'), 'Confirm')
                ,
            ], 'default', true);
        });
    }
}
