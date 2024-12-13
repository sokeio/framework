<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Button;

class ButtonEditable extends BaseUI
{
    protected function initUI()
    {
        $this->child([
            Button::make()->text(__('Edit'))->className('btn btn-primary btn-sm ')
                ->wireClick('rowEditline', function (Button $button) {
                    return [$button->getParams('rowValueId')];
                })->when(function (Button $button) {
                    return !isset($button->getWire()->tableRowEditline[$button->getParams('rowValueId')]);
                }),
            Button::make()->text(__('Save'))->className('btn btn-success btn-sm me-1 ')
                ->confirm(__('Are you sure want to save?'), 'Confirm')
                ->wireClick('saveRowEditline', function (Button $button) {
                    return [$button->getParams('rowValueId')];
                })->when(function (Button $button) {
                    return isset($button->getWire()->tableRowEditline[$button->getParams('rowValueId')]);
                }),
            Button::make()->text(__('Cancel'))->className('btn btn-warning btn-sm ')
                ->confirm(__('Are you sure want to cancel?'), 'Confirm')
                ->wireClick('cancelRowEditline', function (Button $button) {
                    return [$button->getParams('rowValueId')];
                })->when(function (Button $button) {
                    return isset($button->getWire()->tableRowEditline[$button->getParams('rowValueId')]);
                }),
        ]);
    }
}
