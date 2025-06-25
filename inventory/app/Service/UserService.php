<?php

namespace App\Service;
use Validator;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
    public function storeUsers($request) {
        $validate =$this->validateUser($request,'create');
        if ($validate['status'] ==false) {
             $result = [
                'status'  => 422,
                'errors'  => $validate['errors'],
            ];
        }else {
            $response = $this->userData($request,'create');
            if($response['status'] ==true) {
                $result = ['status'=>true,'msg'=>$response['msg'],'data'=>$response['data']];
            }else {
                $result = ['status'=>false,'msg'=>$response['msg']];
            }
        }
        return response()->json($result);
    }
    /* User update input */
    public function userUpdate($request) {
        $validate =$this->validateUser($request,'update');
        if ($validate['status'] ==false) {
             $result = [
                'status'  => 422,
                'errors'  => $validate['errors'],
            ];
        }else {
            $response = $this->userData($request,'update');
            if($response['status'] ==true) {
                $result = ['status'=>true,'msg'=>$response['msg'],'data'=>$response['data']];
            }else {
                $result = ['status'=>false,'msg'=>$response['msg']];
            }
        }
        return response()->json($result);
    }
    /* Validate input */
    public function validateUser($userRequest,$type) {
         $rules = [
            'role' =>'required',
            'name' =>['required','regex:/^[a-zA-Z\s]+$/'],
            'mobile_number'=>'required|integer',
            'profile_img'=>'required|mimes:jpg,jpeg,png'
        ];
        if($type =="create") {
            $rules['email'] = "required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email";
            $rules['password'] = [
                'required',
                'confirmed'
            ];
        }else {
            $rules['email'] = "required|email|regex:/(.+)@(.+)\.(.+)/i";
        }
        $messages = [
            'email.required' =>'Please enter email id',
            'email.email' =>'Please enter valid email',
            'email.regex' =>'Regex not match with email',
            'password.required'=>'Please enter password',
            'password.confirmed' => 'Passwords do not match.',
            'role.required'=>'Please select role',
            'profile_img.required'=>'Please select profile image',
            'profile_img.mimes'=>'Only jpg,jpeg,png are allowed to upload',
            'name.required'=>'Please enter name',
            'name.regex'=>'Only characters are allowed',
            'mobile_number.required'=>'Please enter mobile number',
            'mobile_number.integer'=>'Only numbers are allowed'
        ];
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
    public function userData($userInput,$type) {
        $data['name'] = $userInput['name'];
        $data['email'] = $userInput['email'];
        $data['mobile_number'] = $userInput['mobile_number'];
        $data['password'] = Hash::make($userInput['password']);
        $file = $userInput['profile_img'];
        if(!empty($file) &&  $file !="") {
            $filepath = $this->storeUserImage($file);
            $data['profile_image'] = $filepath;
        }
        if($type =="create") {
            $userList = $this->userRepository->userInsert($data);
            if($userList) {
                $userList->assignRole($userInput['role']);
                $userLogs['user_id'] = $userList->id;
                $userLogs['type'] = 1;
                $this->userRepository->userLogInsert($userLogs);
                $result = [
                    'status'  => true,
                    'msg'  => 'User created successfully',
                    'data' =>$userList
                ];
            }else {
                $result = [
                    'status'  => false,
                    'msg'  => 'Unable to create user',
                ];
            }
        }else {
              $user_id = $userInput['user_id'];
              $userList = $this->userRepository->updateUser($user_id,$data);
                $userLogs['user_id'] = $user_id;
                $userLogs['type'] = 2;
                $this->userRepository->userLogInsert($userLogs);
              if($userList) {
                 $result = [
                    'status'  => true,
                    'msg'  => 'User updated successfully',
                    'data' =>$userList
                ];
              }else {
                 $result = [
                    'status'  => false,
                    'msg'  => 'Unable to create user',
                ];
              }
        }
        return $result;
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
