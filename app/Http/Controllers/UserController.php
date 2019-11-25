<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

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

            User::create(['username'=>$username,
            'password'=>$password,
            'role'=>$role]);

    }

    $result = [
        'username' => $username,
        'password'=>$password,
        'role'=>$role
   ];
   return response()->json($result,201);

}
}
