<?php

namespace BytePlatform\Actions;

use BytePlatform\Concerns\WithAction;

class TimeNowAction
{
    use WithAction;
    public function DoAction($params)
    {
        if (isset($params['format']))
            return [
                'textTime' =>  \Carbon\Carbon::now()->format($params['format'])
            ];
        return [
            'textTime' =>  \Carbon\Carbon::now()->toString()
        ];
    }
}
