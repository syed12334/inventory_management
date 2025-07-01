<?php

namespace App\Service;
use Hash;
use Validator;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;

class UserService
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
        $validate = $this->validateUser($request, 'create');

        if ($validate['status'] === false) {
            return response()->json([
                'status' => 422,
                'errors' => $validate['errors'],
            ]);
        }

        $response = $this->userData($request, 'create');

        if ($response['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => $response['msg'],
                'data'   => $response['data'],
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => $response['msg'],
        ]);
    }

    /* User update input */
    public function userUpdate($request)
    {
        $validate = $this->validateUser($request, 'update');

        if ($validate['status'] === false) {
            return response()->json([
                'status' => 422,
                'errors' => $validate['errors'],
            ]);
        }

        $response = $this->userData($request, 'update');

        if ($response['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => $response['msg'],
                'data'   => $response['data'],
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => $response['msg'],
        ]);
    }


    /* Validate input */
    public function validateUser($userRequest,$type) {
         $rules = [
            'role' =>'required',
        ];
         $messages = [
            'role.required'=>'Please select role',
            // 'profile_img.required'=>'Please select profile image',
            // 'profile_img.mimes'=>'Only jpg,jpeg,png are allowed to upload',
        ];
        if($type =="create") {
            $rules['email'] = "required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email";
            $rules['password'] = [
                'required',
                'confirmed'
            ];
            $rules['name'] = ['required', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:users,name'];
            $rules['mobile_number'] = 'required|integer|unique:users,mobile_number';
            $messages['password.required'] = "Please enter password";
            $messages['password.confirmed'] = "Passwords do not match";
            $messages['email.required'] = "Please enter email id";
            $messages['email.regex'] = "Please enter valid emailid";
            $messages['email.email'] = "Please enter valid email";
            $messages['email.unique'] = "Email already exists try another";
            $messages['name.required'] = "Please enter name";
            $messages['name.regex'] = "Only characters are allowed";
            $messages['name.unique'] = "Username already exists try another";
            $messages['mobile_number.required'] = "Please enter mobile number";
            $messages['mobile_number.integer'] = "Only numbers are allowed";
        }
        $validator = Validator::make($userRequest, $rules,$messages);
        if ($validator->fails()) {
            $result = [
                'status'  => false,
                'errors'  => $validator->errors(),
            ];
        }  
        else {
            $result = [
                'status'  => true,
            ];
        }
        return $result;
    }
    /* user store data */
   /* public function userData($userInput,$type) {

        if($type =="create") {
              $data['name'] = $userInput['name'];
              $data['email'] = $userInput['email'];
              $data['mobile_number'] = $userInput['mobile_number'];
              $data['password'] = Hash::make($userInput['password']);
            $userList = $this->userRepository->userInsert($data);
            if($userList) {
                $userList->assignRole($userInput['role']);
                $userLogs['user_id'] = $userList->id;
                $userLogs['type'] = 1;
                $this->userRepository->userLogInsert($userLogs);
                $resultArr = [
                    'status'  => true,
                    'msg'  => 'User created successfully',
                    'data' =>$userList
                ];
            }else {
                $resultArr = [
                    'status'  => false,
                    'msg'  => 'Unable to create user',
                ];
            }

            return response()->json($resultArr);
            
        }else if($type=="update") {
              $user_id = $userInput['user_id'];
              $usersLists = $this->userRepository->findById($user_id);
              $usersDataLists = $this->userRepository->getUserDataListById($user_id);
              $usersLists->syncRoles($userInput['role']);
              $resss['id'] = "101";
              $resss['name'] = "syed";
              $resss['email'] = "d@gmail.com";
              $userLogs['user_id'] = $user_id;
              $userLogs['type'] = 2;
              $this->userRepository->userLogInsert($userLogs);
              $resultArr['status'] = true;
              $resultArr['msg'] = "User updated successfully";
              $resultArr['data'] = $usersLists;
             return response()->json($resultArr);
        }
        
    }

    */

    public function userData($userInput, $action = 'create')
    {
        if ($action === "create") {

            if (!isset($userInput['name'], $userInput['email'], $userInput['mobile_number'], $userInput['password'], $userInput['role'])) {
                return [
                    'status' => false,
                    'msg'    => 'Missing required fields for user creation.',
                ];
            }

            $data = [
                'name'          => $userInput['name'],
                'email'         => $userInput['email'],
                'mobile_number' => $userInput['mobile_number'],
                'password'      => Hash::make($userInput['password']),
            ];

            $userList = $this->userRepository->userInsert($data);

            if ($userList) {
                $userList->assignRole($userInput['role']);

                $userLogs = [
                    'user_id' => $userList->id,
                    'type'    => 1,
                ];

                $this->userRepository->userLogInsert($userLogs);

                return [
                    'status' => true,
                    'msg'    => 'User created successfully',
                    'data'   => $userList,
                ];
            }

            return [
                'status' => false,
                'msg'    => 'Unable to create user',
            ];
        }

        // Update logic
        if ($action === "update") {
            if (!isset($userInput['user_id'], $userInput['role'])) {
                return [
                    'status' => false,
                    'msg'    => 'Missing user ID or role for update.',
                ];
            }

            $user_id     = $userInput['user_id'];
            $usersLists  = $this->userRepository->findById($user_id);

            if (!$usersLists) {
                return [
                    'status' => false,
                    'msg'    => 'User not found for update.',
                ];
            }

            $usersLists->syncRoles($userInput['role']);
            
            $userLogs = [
                'user_id' => $user_id,
                'type'    => 2,
            ];

            $this->userRepository->userLogInsert($userLogs);

            return [
                'status' => true,
                'msg'    => 'User updated successfully',
                'data'   => $usersLists,
            ];
        }

        return [
            'status' => false,
            'msg'    => 'Invalid action type',
        ];
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
        return $this->userRepository->getUserById($user_id);
    }
    /* Store user image */
    public function storeUserImage($file) {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $filePath = "userprofile/".$filename;
        $file->move(public_path('userprofile'), $filename);
        return $filePath;
    }
}
