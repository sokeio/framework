<?php

namespace Sokeio\Enums;

use Sokeio\Attribute\Label;
use Sokeio\Concerns\AttributableEnum;

enum AlertPosition: string
{
    use AttributableEnum;
    #[Label('Top Right')]
    case TOP_RIGHT = 'top-right';
    #[Label('Top Center')]
    case TOP_CENTER = 'top-center';
    #[Label('Top Left')]
    case TOP_LEFT = 'top-left';
    #[Label('Middle Right')]
    case MIDDLE_RIGHT = 'middle-right';
    #[Label('Middle Center')]
    case MIDDLE_CENTER = 'middle-center';
    #[Label('Middle Left')]
    case MIDDLE_LEFT = 'middle-left';
    #[Label('Bottom Right')]
    case BOTTOM_RIGHT = 'bottom-right';
    #[Label('Bottom Center')]
    case BOTTOM_CENTER = 'bottom-center';
    #[Label('Bottom Left')]
    case BOTTOM_LEFT = 'bottom-left';
}
