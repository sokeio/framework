<?php

namespace Sokeio;
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
    private $from_user;
    public satic function Make(){
        return new static();
    }
    public function Title($title){
        $this->title=$title;
        return $this;
    }
    public function Description($description){
        $this->description=$description;
        return $this;
    }
    public function ToRole($to_role){
        $this->to_role=$to_role;
        return $this;
    }
    public function ToUser($to_user){
        $this->to_user=$to_user;
        return $this;
    }
    public function Type($type){
        $this->type=$type;
        return $this;
    }
    public function View($view){
        $this->view=$view;
        return $this;
    }
    public function FromUser($from_user){
        $this->from_user=$from_user;
        return $this;
    }
    public function MetaData($meta_data){
        $this->meta_data=$meta_data;
        return $this;
    }
    public function send(){
        $noti = new NotificationModel();
        $noti->title = $this->title;
        $noti->description = $this->description;
        $noti->meta_data = $this->meta_data;
        $noti->to_user = $this->to_user;
        $noti->to_role = $this->to_role;
        $noti->view = $this->view;
        $noti->type = $this->type;
        $noti->from_user = this->from_user??(auth()->check() ? auth()->user()->id : -1);
        $noti->save();
        NotificationAdd::dispatch($noti);
        NotificationAdd::broadcast($noti);
    }
    
}
