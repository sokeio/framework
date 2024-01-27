<?php

namespace Sokeio\Models;

use Illuminate\Database\Eloquent\Model;

class MenuLocation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'locations'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'locations' => 'array',
    ];
    public function menus()
    {
        return $this->hasMany(Menu::class)->orderBy('parent_id')->orderBy('order');
    }
}
