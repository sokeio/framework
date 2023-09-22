<?php

namespace BytePlatform\Models;


class Notification extends \BytePlatform\Model
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
