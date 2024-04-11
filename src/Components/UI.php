<?php

namespace Sokeio\Components;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Components\Concerns\WithCommon;
use Sokeio\Components\Concerns\WithGrid;
use Sokeio\Components\Concerns\WithField;

class UI
{
    use Macroable, WithGrid, WithCommon, WithField;
    public const KEY_FIELD_NAME = '###______###';
    public const MODAL_SMALL = 'modal-sm';
    public const MODAL_LARGE = 'modal-lg';
    public const MODAL_EXTRA_LARGE = 'modal-xl';
    public const MODAL_FULLSCREEN = 'modal-fullscreen';
    public const MODAL_FULLSCREEN_SM = 'modal-fullscreen-sm-down';
    public const MODAL_FULLSCREEN_MD = 'modal-fullscreen-md-down';
    public const MODAL_FULLSCREEN_LG = 'modal-fullscreen-lg-down';
    public const MODAL_FULLSCREEN_XL = 'modal-fullscreen-xl-down';
    public const MODAL_FULLSCREEN_XXL = 'modal-fullscreen-xxl-down';
    public const BUTTON_SMALL = 'sm';
    public const BUTTON_LARGE = 'lg';
}
