<?php
namespace App\Repository;
use App\Models\User;
class UserRepository
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getUsers() {
        return $this->user->get();
    }
}
