<?php

namespace BytePlatform\Actions;

use BytePlatform\Concerns\WithAction;

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
