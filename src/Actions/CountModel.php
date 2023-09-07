<?php

namespace BytePlatform\Actions;

use BytePlatform\Traits\WithAction;

class CountModel
{
    use WithAction;
    public function DoAction($params)
    {
        if (isset($params['modelCount']))
            return [
                'number' => app($params['modelCount'])?->count()
            ];
        return 0;
    }
}
