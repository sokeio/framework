<?php

namespace BytePlatform\Models;


class Setting extends \BytePlatform\Model
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
