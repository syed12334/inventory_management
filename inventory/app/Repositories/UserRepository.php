<?php
    namespace App\Repositories;
    use App\Models\User;
    use App\Models\UserLogs;
    use Spatie\Permission\Models\Role;
    use Spatie\Permission\Models\Permission;
class UserRepository
{
    protected $user;
    protected $role;
    protected $permission;
    protected $userlogs;

    public function __construct(User $user,Role $role,Permission $permission,UserLogs $UserLogs)
    {
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->userlogs = $UserLogs;
    }

    public function getUsers($filters=[]) {
      $paginate = 10;
      $user = $this->user->query();
      if(!empty($filters) && is_array($filters)) {
            if (!empty($filters['paging'])) {
                $paginate = (int) $filters['paging'];
            }
            if (isset($filters['status'])) {
                if ($filters['status'] == -1) {
                    $user->whereIn('status', ['0', '1']);
                } else {
                    $user->where('status', $filters['status']);
                }
            }
            if (!empty($filters['roles'])) {
                $roles = $filters['roles'];
                $user->whereHas('roles', function ($query) use ($roles) {
                    $query->where('id', $roles);
                });
            }
        }else {
            $user->where('status', '!=', 2);
        }
        return $user->with('roles')->orderBy('id', 'desc')->paginate($paginate);
    }

    public function getUserByEmail($email) {
        return $this->user->where('email',$email)->count();
    }

    public function getUsersByRole($filters = [])
    {
       $paginate = 10;
       
        if (!empty($filters['role_scope'])) {

            $roleScope = strtolower($filters['role_scope']);

            $userQuery = $this->user->whereHas('roles', function ($query) use ($roleScope) {
                $query->where('name', 'like', '%' . $roleScope . '%');
            });

        } else {

            $userQuery = $this->user->query();
        }

        $userQuery->where('status', '!=', 2);

        return $userQuery->with('roles')->orderBy('id', 'desc')->paginate($paginate);
    }
    
    public function userInsert($data) {
        return $this->user->create($data);
    }

    public function updateUser($user_id,$data) {
        return $this->user->where('id',$user_id)->update($data);
    }

    public function userLogInsert($data) {
        return $this->userlogs->create($data);
    }

    public function getRolesByName($name)
    {
        return Role::where('name', 'like', '%' . $name . '%')->first();
    }
    
    public function getRoles() {
        return $this->role->all();
    }
    
    public function deleteUsers($user) {
       $statusMessages = [
            0 => ['msg' => __('users.inactiveUser'), 'statuskey' => 'inactiveUser'],
            1 => ['msg' => __('users.activeUser'),   'statuskey' => 'activateUser'],
            2 => ['msg' => __('users.deleteUser'),   'statuskey' => 'deleteUser'],
        ];
        if (isset($statusMessages[$user['status']])) {
            $this->user->where('id', $user['user_id'])->update(['status' => $user['status']]);
            return [
                'status'    => $user['status'],
                'msg'       => $statusMessages[$user['status']]['msg'],
                'statuskey' => $statusMessages[$user['status']]['statuskey'],
            ];
        }
    }

    public function deleteMultipleusers($request) {
     
        $statusMessages = [
            0 => ['msg' => __('users.usermultipledelete'),   'statuskey' => 'usermultipledelete']
        ];
        $userId = $request['deleteUser'];
        
        $this->user->whereIn('id',$userId)->update(['status'=>2]);
         return [
                'status'    => true,
                'msg'       => $statusMessages[0]['msg'],
                'statuskey' => $statusMessages[0]['statuskey'],
            ];
    }
    
    public function getUserById($user_id) {
        return $this->user->with('roles')->where('id',$user_id)->first();
    }

    public function findById($user_id) {
         return $this->user->where('id',$user_id)->first();
    }

    public function getUserDataListById($user_id) {
        return $this->user->where('id',$user_id)->get();
    }
}
