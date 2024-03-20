<?php

namespace Sokeio\Models;


class Language extends \Sokeio\Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'code',
        'default',
        'status',
    ];
}
