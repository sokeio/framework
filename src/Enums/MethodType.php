<?php

namespace Sokeio\Enums;

use Sokeio\Core\Attribute\Label;
use Sokeio\Core\Concerns\AttributableEnum;

enum MethodType: string
{
    use AttributableEnum;
    #[Label('GET')]
    case GET = 'GET';
    #[Label('POST')]
    case POST = 'POST';
    #[Label('PUT')]
    case PUT = 'PUT';
    #[Label('PATCH')]
    case PATCH = 'PATCH';
    #[Label('DELETE')]
    case DELETE = 'DELETE';
    #[Label('OPTIONS')]
    case OPTIONS = 'OPTIONS';
    #[Label('HEAD')]
    case HEAD = 'HEAD';
}
