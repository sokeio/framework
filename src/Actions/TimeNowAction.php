<?php

namespace BytePlatform\Actions;

use BytePlatform\Traits\WithAction;

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
