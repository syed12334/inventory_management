<?php

namespace App\Repository;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository
{
    protected $role;
    protected $permission;
    public function __construct(Role $role,Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }
    public function getRoles() {
        return $this->role->with('permissions')->get();
    }
    public function storeRoles($data) {
        return $this->role->create($data);
    }
    public function getPermissions() {
        return $this->permission->get();
    }
}
