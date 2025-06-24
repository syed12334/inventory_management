<?php

namespace App\Http\Controllers;

use App\Service\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;
    public function __construct(RoleService $RoleService) {
        $this->role = $RoleService;
    }
    public function index() {
        $role = $this->role->index();
        $permission = $this->role->getPermission();
        return view('Role/index',compact('role','permission'));
    }
    public function store(Request $request) {
        return $this->role->store($request->all());
    }
}
