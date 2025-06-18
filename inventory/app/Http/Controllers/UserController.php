<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Interface\UserInterface;
class UserController extends Controller
{
    public $user;
    public $post;
    public $UserInterface;
    public function __construct(User $users,Post $posts,UserInterface $UserInterface) {
        $this->user  = $users;
        $this->post  = $posts;
        $this->user_interface  = $UserInterface;
    }
    /* Fetch all user table */
    public function index() {
        return $this->user_interface->index();
    }
    /* Add user */
    public function adduser() {
        return view('adduser');
    }
   /* Store user in database */
    public function store(Request $request) {
        return $this->user_interface->create($request);
    }
    /* Update user in database */
    public function update(Request $request) {
        return $this->user_interface->create($request);
    }
    /* Destroy user in database */
    public function destroy(Request $request) {
        return $this->user_interface->create($request);
    }
}
 