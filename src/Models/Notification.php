<?php

namespace Sokeio\Models;


class Notification extends \Sokeio\Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'from_user',
        'to_user',
        'meta_data',
        'type',
        'view',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta_data' => 'array',
        'to_user' => 'array',
        'to_role' => 'array',
    ];
    public function UserRead()
    {
        return $this->hasMany(NotificationUser::class);
    }
}
