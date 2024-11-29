<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Str;

trait KeyWithName
{
    public static function bootPlatformKeySlug()
    {
        static::creating(function ($model) {
            if ($model->key == null) {
                $model->key = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->key == null) {
                $model->key = Str::slug($model->name);
            }
        });
        static::saving(function ($model) {
            if ($model->key == null) {
                $model->key = Str::slug($model->name);
            }
        });
    }
}
