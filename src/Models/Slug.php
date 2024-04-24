<?php

namespace Sokeio\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slug extends \Sokeio\Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'reference_id',
        'reference_type',
        'prefix',
        'locale',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
    public function reference(): BelongsTo
    {
        return $this->morphTo();
    }
}
