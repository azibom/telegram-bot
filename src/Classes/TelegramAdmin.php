<?php

namespace TelegramBot\Classes;

use User;

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

    public function setAdminFlag(User $user)
    {
        return $this->repo->updateUser($user, null, null, "yes");
    }

    public function checkUserIsAdmin($user)
    {
        return $user->getIsAdmin() === "yes";
    }
}