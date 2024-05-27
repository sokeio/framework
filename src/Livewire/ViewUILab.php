<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Log;
use Sokeio\Component;
use Sokeio\Components\Concerns\WithViewUI;
use Sokeio\Components\UI;

class ViewUILab extends Component
{
    use WithViewUI;
    public  function TestLog($text)
    {
        Log::info(__FILE__ . ":" . __LINE__ . "::" . $text);
    }
    protected function viewUI()
    {
        return UI::div(UI::loadFormfromJson([
            [
                'type' => 'row',
                'children' => [
                    [
                        'type' => 'text',
                        'attrs' => [
                            [
                                'type' => 'text',
                                'name' => 'label',
                                'value' => '"Xin chào";'
                            ],
                            [
                                'type' => 'col',
                                'name' => 'col4',
                                'value' => ''
                            ]
                        ]
                    ]
                ]
            ]
        ]))->className('p-3');
    }
}
