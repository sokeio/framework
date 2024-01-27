<?php

namespace Sokeio\Models;

use Illuminate\Database\Eloquent\Model;

class MenuLocation extends Model
{
    public $timestamps = false;
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'locations' => 'array',
    ];
}
