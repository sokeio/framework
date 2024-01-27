<?php

namespace Sokeio\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'parent_id', 'menu_location_id', 'icon', 'name', 'link', 'attr_name', 'class_name', 'data', 'order'
    ];
    public function menuLocation()
    {
        return $this->belongsTo(MenuLocation::class, 'menu_location_id');
    }
}
