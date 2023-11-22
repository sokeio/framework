<?php

namespace Sokeio\Models;


class Translation extends \Sokeio\Model
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
