<?php

namespace Sokeio\Platform;

class GateManager
{
    private static $instance;
    public static function getInstance(PlatformManager $manager = null)
    {
        if (!self::$instance && $manager) {
            self::$instance = new self($manager);
        }
        return self::$instance;
    }
    private $user;
    private function __construct(protected PlatformManager $manager) {}
    public function getUserInfo()
    {
        return $this->user;
    }
    public function setUser($user)
    {
        $this->user = $user;
    }
    public function getUserByToken($token)
    {
        //TODO: get user by token
    }
    public function getPermission()
    {
        //TODO: get user permission
    }
    public function getRole()
    {
        //TODO: get user role
    }
    public function check($permssion)
    {
        //TODO: check user permission
    }
    public function role($role)
    {
        //TODO: check user role
    }
}
