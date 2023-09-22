<?php

namespace BytePlatform\Traits;


trait WithModelHook
{
    protected static function bootWithModelHook()
    {
        static::creating(function ($model) {
            do_action('BYTE_MODEL_BASE_CREATING', $model);
        });
        static::save(function ($model) {
            do_action('BYTE_MODEL_BASE_CREATING', $model);
        });
    }
}
