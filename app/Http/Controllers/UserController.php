<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){

        $username =$request->username;
        $password = $request->password;
        $role = $request->role;

        $inputs = $request->all();

        $rules = [
            'username'=>'required',
            'password'=>'required',
            'role'=>'required'
        ];

        $validator = Validator::make($inputs,$rules);

        if($validator->fails()){

            return response(['message'=>$validator->errors()],409);
        }else{

            $addRole = Role::create(['name'=>$role]);

            $addUser = User::create(['username'=>$username,
            'password'=>Hash::make($request->password),
            'role'=>$role,
            'role_id'=>$addRole['id']]);



    }

    $result = [
        'role'=>$role,
        'username' => $username,
        'password'=>$password

   ];
   return response()->json($result,201);

}

public function login(Request $request){

    $username =$request->username;
    $password = $request->password;




}

}
