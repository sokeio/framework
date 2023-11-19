<?php

namespace BytePlatform\Models;


class Translation extends \BytePlatform\Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name',
        'key',
        'value',
        'lang_id'
    ];
}
