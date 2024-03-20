<?php

namespace Sokeio\Models;


class Setting extends \Sokeio\Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'locked',
        'value',
    ];
    protected $casts = [
        'value' => 'array',
    ];
}
