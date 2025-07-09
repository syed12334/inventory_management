<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\LoginService;
class LoginController extends Controller
{
    protected $login;
    public function __construct(LoginService $login) {
        $this->login = $login;
    }
    /* Login page **/
    public function index() {
        return view('login');
    }
    public function login(Request $request) {
        return $this->login->getLogin($request->all());
    }
    public function logout() {
        return $this->login->logout();
    }
}
