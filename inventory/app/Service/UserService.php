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
    /* Store users */
    public function storeUsers($request) {
         $rules = [
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
            'password'=>[
                'required',
                'confirmed'
            ],
            'role' =>'required',
            'name' =>'required',
            'phone'=>'required|integer',
            'profile_img'=>'required|mimes:jpg,jpeg,png'
        ];
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
            'phone.required'=>'Please enter mobile number',
            'phone.integer'=>'Only numbers are allowed'
        ];
        $validator = Validator::make($request, $rules,$messages);
        if ($validator->fails()) {
             $result = [
                'status'  => 422,
                'errors'  => $validator->errors(),
            ];
        }else {
            $data['name'] = $request['name'];
            $data['email'] = $request['email'];
            $data['password'] = Hash::make($request['password']);
            $file = $request['profile_img'];
            if(!empty($file) &&  $file !="") {
                 $filename = time() . '.' . $file->getClientOriginalExtension();
                 $filePath = "userprofile/".$filename;
                 $file->move(public_path('userprofile'), $filename);
                 $data['profile_image'] = $filePath;
            }
            $userList = $this->userRepository->userInsert($data);
            $userList->assignRole($request['role']);
            $userLogs['user_id'] = $userList->id;
            $userLogs['type'] = 1;
             $this->userRepository->userLogInsert($userLogs);
            $result = [
                'status'  => true,
                'msg'  => 'User created successfully',
                'data' =>$userList
            ];
        }
        return response()->json($result);
    }
    /* Delete single user */
    public function delete($user) {
        return $this->userRepository->deleteUsers($user);
    }
    /* Delete multiple list */
    public function deleteMultiple($userRequest) {
        return $this->userRepository->deleteMultipleusers($userRequest);
    }
}
