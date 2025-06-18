<?php
namespace App\Http\Controllers;
use App\Service\UserService;
use Illuminate\Http\Request;
class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $users) {
        $this->userService  = $users;
    }
    /* Fetch all user table */
    public function index() {
        return $this->userService->getUsers();
    }
  
}
 