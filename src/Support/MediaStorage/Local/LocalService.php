<?php

namespace Sokeio\Support\MediaStorage\Local;

use Sokeio\Support\MediaStorage\MediaStorageService;

class LocalService extends MediaStorageService
{
    public function getName()
    {
        return 'Local';
    }
}
