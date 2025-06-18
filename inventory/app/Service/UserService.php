<?php

namespace App\Service;

use App\Models\User;
use App\Repository\UserRepository;

class UserService
{
   public $userRepository;
    public function __construct(UserRepository $UserRepository)
    {
        $this->userRepository = $UserRepository;
    }
    public function getUsers() {
        return $this->userRepository->getUsers();
    }
    
}
