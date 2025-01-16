<?php

namespace Sokeio\Models;

use Sokeio\Model;

class MenuItem extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'menu_id',
        'parent_id',
        'icon',
        'color',
        'name',
        'link',
        'attribute',
        'classname',
        'order',
        'menuable_type',
        'menuable_id',
    ];
    protected $appends = ['url', 'title'];
    public function getUrlAttribute()
    {
        if ($this->link) {
            return $this->link;
        }
        return $this->menuable?->url ?? '#';
    }
    public function getTitleAttribute()
    {
        if ($this->name) {
            return $this->name;
        }
        return $this->menuable?->title ?? '';
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function menuable()
    {
        return $this->morphTo();
    }
}
