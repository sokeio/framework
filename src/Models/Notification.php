<?php

namespace Sokeio\Models;


class Notification extends \Sokeio\Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta_data' => 'array',
    ];
}
