<?php

namespace BytePlatform\Models;


class Language extends \BytePlatform\Model
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