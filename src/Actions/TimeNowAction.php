<?php

namespace Sokeio\Actions;

use Sokeio\Concerns\WithAction;

class TimeNowAction
{
    use WithAction;
    public function DoAction($params)
    {
        if (isset($params['attrs']['format']))
            return [
                'textTime' =>  \Carbon\Carbon::now()->format($params['attrs']['format'])
            ];
        return [
            'textTime' =>  \Carbon\Carbon::now()->toString()
        ];
    }
}
