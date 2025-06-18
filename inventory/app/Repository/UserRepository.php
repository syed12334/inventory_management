<?php
namespace App\Repository;
use App\Models\User;
use App\Interface\UserInterface;
use Validator;

class UserRepository implements UserInterface
{
    /*
      Create a new class instance.
     */
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /*
        Fetch all user data
    */
    public function index() {
        return $this->user->get();
    }
    /*
        Store user data
    */
    public function create($request) {
         $messages = [
            'name.*.required' =>'Please enter name',
         ];
          $validator = Validator::make($request->all(), [
                'name.*' => 'required|string|max:255',
           ],$messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    }
}
