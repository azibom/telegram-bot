<?php

namespace TelegramBot\Classes;

class TelegramAdmin {
    private $rootPass;
    private $repo;

    public function __construct($rootPass)
    {
        $this->repo = new TelegramRepository();
        $this->rootPass = $rootPass;
    }

    public function checkPass($pass)
    {
        return $pass == $this->rootPass;
    }

    public function setAdminFlag($user)
    {
        $user->setIsAdmin("yes");
        return $this->repo->updateUser($user);
    }

    public function checkUserIsAdmin($user)
    {
        return $user->getIsAdmin() == "yes";
    }
}