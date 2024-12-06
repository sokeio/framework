<?php

namespace Sokeio\Enums;

use Sokeio\Attribute\Label;
use Sokeio\Concerns\AttributableEnum;

enum AlertType: string
{
    use AttributableEnum;
    #[Label('Success')]
    case SUCCESS = 'success';
    #[Label('Info')]
    case INFO = 'info';
    #[Label('Warning')]
    case WARNING = 'warning';
    #[Label('Danger')]
    case DANGER = 'danger';
}
