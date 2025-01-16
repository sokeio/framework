<?php

namespace Sokeio\Models;

use Sokeio\Model;

class Menu extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'data',
        'locations',
    ];
    protected $casts = [
        'data' => 'array',
        'locations' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
