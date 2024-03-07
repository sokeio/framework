<?php

namespace Sokeio;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Sokeio\Events\NotificationAdd;
use Sokeio\Models\Notification as NotificationModel;

class Notification
{
    private $title;
    private $description;
    private $toRole;
    private $toUser;
    private $type;
    private $view;
    private $metaData;
    private $fromUser;
    public static function make()
    {
        return new static();
    }
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }
    public function description($description)
    {
        $this->description = $description;
        return $this;
    }
    public function toRole($toRole)
    {
        $this->toRole = $toRole;
        return $this;
    }
    public function toUser($toUser)
    {
        $this->toUser = $toUser;
        return $this;
    }
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }
    public function view($view)
    {
        $this->view = $view;
        return $this;
    }
    public function fromUser($fromUser)
    {
        $this->fromUser = $fromUser;
        return $this;
    }
    public function metaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }
    public function send()
    {
        $noti = new NotificationModel();
        $noti->title = $this->title;
        $noti->description = $this->description;
        $noti->meta_data = $this->metaData;
        $noti->to_user = $this->toUser;
        $noti->to_role = $this->toRole;
        $noti->view = $this->view;
        $noti->type = $this->type;
        $noti->from_user = $this->getUserId();
        $noti->save();
        NotificationAdd::dispatch($noti);
        NotificationAdd::broadcast($noti);
    }
    public function getUserId()
    {
        return $this->fromUser ?? (auth()->check() ? auth()->user()->id : -1);
    }
    public function tickReadAll()
    {
        $userId = $this->getUserId();
        DB::statement("
            INSERT INTO notification_users (user_id, notification_id, read_at)
            SELECT ?, notifications.id, NOW()
            FROM notifications
            WHERE NOT EXISTS (
                SELECT 1
                FROM notification_users
                WHERE notification_users.notification_id = notifications.id
                AND notification_users.user_id = ?
            )
        ", [$userId, $userId]);
    }
    public function tickRead($id)
    {
        $user_id = $this->getUserId();
        $noti = NotificationModel::find($id);
        if ($noti) {
            $noti->UserRead()->firstOrCreate([
                'user_id' => $user_id,
            ], [
                'read_at' => Carbon::now()
            ]);
        }
    }

    public function getNoticationAll($userId = -1, $page = 0, $pageSize = 5, $status = 0)
    {
        $query = NotificationModel::with(['UserRead' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);
        if ($status == -1) {
            $query =  $query->WhereDoesntHave('UserRead');
        }
        if ($status == 1) {
            $query =  $query->whereHas('UserRead');
        }
        return $query->latest()->paginate($pageSize, ['*'], 'page', $page);
    }
    public function render($page, $type)
    {
        $notifications = $this->getNoticationAll($this->getUserId(), $page, 15, $type);
        return view('sokeio::notifications.body', ['notifications' =>  $notifications])->render();
    }
}
