<?php

namespace Sokeio\Translatable;

use Sokeio\Model;

class ModelTranslatable extends Model
{
    public $timestamps = false;
    protected $table = '';
    protected $fillable = ['*'];
}
