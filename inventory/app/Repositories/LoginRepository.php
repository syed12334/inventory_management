<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\UserLogs;
class LoginRepository
{
    protected $user;
    protected $userLogs;
    public function __construct(User $user,UserLogs $userLogs)
    {
        $this->user = $user;
        $this->userLogs = $userLogs;
    }
}
