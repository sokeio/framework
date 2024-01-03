<?php

namespace Sokeio\Models;


class NotificationUser extends \Sokeio\Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'notification_id',
        'user_id',
        'read_at',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];
    public function Notification()
    {
        return $this->belongsTo(Notification::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
