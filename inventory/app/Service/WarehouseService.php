<?php

namespace App\Service;
use Hash;
use Illuminate\Support\Facades\Validator;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;

class WarehouseService
{
   public $userRepository;
    public function __construct(UserRepository $UserRepository)
    {
        $this->userRepository = $UserRepository;
    }
    /* Get all users */
    public function getUsers($request) {
        return  $this->userRepository->getUsers($request);
    }

    public function getUsersByRole($request)
    {
        return $this->userRepository->getUsersByRole($request);
    }

    public function getRolesByName($name){
        return $this->userRepository->getRolesByName($name);
    }

    /* Roles list */
    public function getRoles() {
         return  $this->userRepository->getRoles();
    }
    /* Permission list */
    public function getPermissions() {
        return $this->userRepository->getPermissions();
    }

    /* User insert input */
    public function storeUsers($request)
    {
        $validation = $this->validateUser($request, 'create');

        if (!$validation['status']) {
             return response()->json([
                'status' => 422,
                'errors' => $validation['errors'],
            ]);
        }

        $data = $this->prepareBuildData($request, 'create');

        $user = $this->userRepository->userInsert($data);

        if ($user) {
            $user->assignRole($request['role']);

            $userLog = [
                'user_id' => $user->id,
                'type'    => 1,
            ];

            $this->userRepository->userLogInsert($userLog);

            return [
                'status' => true,
                'msg'    => 'User created successfully',
                'data'   => $user,
            ];
        }

        return [
            'status' => false,
            'msg'    => 'Unable to create user',
            'data'   => null,
        ];
    }


    /* User update input */
   public function userUpdate($request)
   {
        $userId = $request['user_id'] ?? null;

        if (!$userId) {
            return response()->json([
                'status' => false,
                'msg'    => 'User ID is required',
            ], 400);
        }

        $userData = $this->getUserById($userId);

        if (!$userData) {
            return response()->json([
                'status' => false,
                'msg'    => 'User not found',
            ], 404);
        }

        $validation = $this->validateUser($request, 'update');

        if (!$validation['status']) {
            return response()->json([
                'status' => false,
                'errors' => $validation['errors'],
            ], 422);
        }

        $data = $this->prepareBuildData($request, 'update', $userData);

        $updated = $this->userRepository->updateUser($userId, $data);

        if ($updated) {
            $this->userRepository->userLogInsert([
                'user_id' => $userId,
                'type'    => 2,
            ]);

            return response()->json([
                'status' => true,
                'msg'    => 'User updated successfully',
                'data'   => $data,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'Unable to update user',
            'data'   => null,
        ]);
    }




    /* Validate input */
    public function validateUser($userRequest, $type)
    {
        $rules = [];
        $messages = [];
        
        $userId = isset($userRequest['id']) ? $userRequest['id'] : null;

        if ($type === "create") {
            $rules['email'] = "required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email";
            $rules['password'] = [
                'required',
                'confirmed'
            ];
            $rules['name'] = ['required', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:users,name'];
            $rules['mobile_number'] = 'required|integer|unique:users,mobile_number';
            $rules['bank_detail'] = 'required';
        }

        if ($type === "update") {
            $rules['bank_detail'] = 'required';
        }

        $messages = [
            'password.required'         => "Please enter password",
            'password.confirmed'        => "Passwords do not match",
            'email.required'            => "Please enter email id",
            'email.regex'               => "Please enter valid email id",
            'email.email'               => "Please enter valid email",
            'email.unique'              => "Email already exists, try another",
            'name.required'             => "Please enter name",
            'name.regex'                => "Only characters are allowed",
            'name.unique'               => "Username already exists, try another",
            'mobile_number.required'    => "Please enter mobile number",
            'mobile_number.integer'     => "Only numbers are allowed",
            'mobile_number.unique'      => "Mobile number already exists",
            'bank_detail.required'      => "Please enter bank detail",
        ];

        if (empty($rules)) {
            return [
                'status' => false,
                'errors' => ['type' => ['Invalid validation type provided.']],
            ];
        }

        $validator = Validator::make($userRequest, $rules, $messages);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        return ['status' => true];
    }

    public function prepareBuildData(array $request, string $type = 'create', $existing = null)
    {
        $data = [
            'name'               => $request['name'] ?? ($existing->name ?? null),
            'email'              => $request['email'] ?? ($existing->email ?? null),
            'mobile_number'      => $request['mobile_number'] ?? ($existing->mobile_number ?? null),
            'warehouse_store_id' => $request['warehouse_store_id'] ?? ($existing->warehouse_store_id ?? null),
            'jsontext'           => null,
        ];

        if ($type === 'create' && !empty($request['password'])) {
            $data['password'] = Hash::make($request['password']);
        } 
        $excludedKeys = [
            'name', 'email', 'user_id', 'mobile_number', 'password',
            'password_confirmation', 'warehouse_store_id', 'jsontext', '_token'
        ];

        $extraFields = array_diff_key($request, array_flip($excludedKeys));

        if (!empty($extraFields)) {
            $existingJsonFields = $existing->jsontext ?? null;
            $existingJsonFields = $existingJsonFields ? json_decode($existingJsonFields, true) : [];
            $data['jsontext'] = json_encode(array_merge($existingJsonFields, $extraFields));
        }

        return $data;
    }


    /* Delete single user */
    public function delete($user) {
        return $this->userRepository->deleteUsers($user);
    }
    /* Delete multiple list */
    public function deleteMultiple($userRequest) {
        
        return $this->userRepository->deleteMultipleusers($userRequest);
    }
    
    /* get user by id */
    public function getuserbyid($user_id) {

        $data = $this->userRepository->getUserById($user_id);
        
        $jsonFields = json_decode($data->jsontext, true);

        if (is_array($jsonFields)) {
            foreach ($jsonFields as $key => $value) {
                $data->$key = $value;
            }
        } 

        return $data;
    }

    /* Store user image */
    public function storeUserImage($file) {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $filePath = "userprofile/".$filename;
        $file->move(public_path('userprofile'), $filename);
        return $filePath;
    }
}
