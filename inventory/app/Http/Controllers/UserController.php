<?php
namespace App\Http\Controllers;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $users) {
        $this->userService  = $users;
    }
    /* Fetch all user table */
    public function index(Request $request) {
        $users =  $this->userService->getUsers($request->all());
        $roles =  $this->userService->getRoles();
        return view('User/index',compact('users','roles'));
    }
    /* Store users into databse */
    public function store(Request $request) {
        return $this->userService->storeUsers($request->all());
    }
    /* User status change */
    public function userStatus(Request $request) {
        $userStatusList = $this->userService->delete($request->all());
        return back()->with('success', $userStatusList['msg']);
    }
    /* Multiple delete */
    public function deleteMultiple(Request $request) {
        $userStatusList = $this->userService->deleteMultiple($request->all());
        return back()->with('success', $userStatusList['msg']);
    }
     /* Edit user */
    public function editUser(Request $request) {
        $user_id = $request->user_id;
        $userData =  $this->userService->getuserbyid($user_id);
        if(count($userData) >0) {
            $result['status'] =true;
            $result['msg'] ="User data found";
            $result['data'] =$userData;
        }else {
            $result['status'] =false;
            $result['msg'] ="No data found";
        }
        return response()->json($result);
    }
    /* Update user */
    public function update(Request $request) {
        $this->userService->userUpdate($request->all());
    }
}
 