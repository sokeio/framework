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
    private $to_role;
    private $to_user;
    private $type;
    private $view;
    private $meta_data;
    private $from_user;
    public static function Make()
    {
        return new static();
    }
    public function Title($title)
    {
        $this->title = $title;
        return $this;
    }
    public function Description($description)
    {
        $this->description = $description;
        return $this;
    }
    public function ToRole($to_role)
    {
        $this->to_role = $to_role;
        return $this;
    }
    public function ToUser($to_user)
    {
        $this->to_user = $to_user;
        return $this;
    }
    public function Type($type)
    {
        $this->type = $type;
        return $this;
    }
    public function View($view)
    {
        $this->view = $view;
        return $this;
    }
    public function FromUser($from_user)
    {
        $this->from_user = $from_user;
        return $this;
    }
    public function MetaData($meta_data)
    {
        $this->meta_data = $meta_data;
        return $this;
    }
    public function send()
    {
        $noti = new NotificationModel();
        $noti->title = $this->title;
        $noti->description = $this->description;
        $noti->meta_data = $this->meta_data;
        $noti->to_user = $this->to_user;
        $noti->to_role = $this->to_role;
        $noti->view = $this->view;
        $noti->type = $this->type;
        $noti->from_user = $this->getUserId();
        $noti->save();
        NotificationAdd::dispatch($noti);
        NotificationAdd::broadcast($noti);
    }
    public function getUserId()
    {
        return $this->from_user ?? (auth()->check() ? auth()->user()->id : -1);
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
    public function Render($page, $type)
    {
        return view('sokeio::notifications.body', ['notifications' => $this->getNoticationAll($this->getUserId(), $page, 15, $type)])->render();
    }
}
