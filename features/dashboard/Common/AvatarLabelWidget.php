<?php

namespace Sokeio\Dashboard\Common;

use Sokeio\Dashboard\WidgetUI;

class LabelWidget extends WidgetUI
{
    public function getWidgetLabel()
    {
        return 'demo';
    }
    public function getWidgetValue()
    {
        return 'Hello World';
    }
    public function view()
    {
        $view = <<<HTML
            <div class="so-widget">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <span class="avatar avatar-lg"></span>
                    </div>
                    <div class="col">
                        <h4 class="card-title m-0">
                            <a href="#">Mallory Hulme</a>
                        </h4>
                        <div class="text-secondary">
                            Working in Kare
                        </div>
                        <div class="small mt-1">
                            <span class="badge bg-green"></span> Online
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="#" class="btn">
                        Subscribe
                        </a>
                    </div>
                    <div class="col-auto">
                    </div>
                </div>
            </div>
        HTML;

        return $view;
    }
}
