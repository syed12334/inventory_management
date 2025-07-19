<?php

namespace App\Services;
use App\Repositories\RoleRepositories;
use Validator;
class RoleService
{
    protected $role;
    public function __construct(RoleRepository $RoleRepository)
    {
        $this->role = $RoleRepository;
    }
    /* get all roles **/
    public function index() {
        return $this->role->getRoles();
    }
    /* Get permissions */
    public function getPermission() {
        return $this->role->getPermissions();
    }
    /* store roles **/
    public function store($request) {
         $rules = [
            'role' =>'required|unique:roles,name',
            'permissions'=>'required'
        ];
        $messages = [
            'role.required'=>'Please enter role',
            'permissions.required'=>'Please select permission',
        ];
        $validator = Validator::make($request, $rules,$messages);
        if ($validator->fails()) {
             $result = [
                'status'  => 422,
                'errors'  => $validator->errors(),
            ];
        }else {
            $roleData['name'] = $request['role'];
            $roles = $this->role->storeRoles($roleData);
             $roles->syncPermissions($request['permissions']);
             $result = [
                'status'  => true,
                'msg'  => 'Role created successfully',
                'data'=>$roles
            ];
        }
        return response()->json($result);
    }
}
