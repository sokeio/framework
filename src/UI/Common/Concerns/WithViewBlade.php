<?php

namespace Sokeio\UI\Common\Concerns;

trait WithViewBlade
{
    public function viewBlade($blade, $params = [])
    {
        return $this->child([
            function () use ($blade, $params) {
                if (is_callable($params)) {
                    $params = call_user_func($params, $this);
                }
                return $this->viewRender($blade, $params);
            }
        ]);
    }
}
