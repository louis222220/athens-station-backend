<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Str;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $username = $request->username;
        $password = $request->password;
        $role = $request->role;

        $inputs = $request->all();

        $rules = [
            'username' => 'required',
            'password' => 'required',
            'role' => 'required'
        ];

        $validator = Validator::make($inputs, $rules);

        if ($validator->fails()) {

            return response(['message' => $validator->errors()], 409);
        } else {

            $findRole = Role::where('name',$role)->first();

            $addUser = User::create([
                'username' => $username,
                'password' => Hash::make($request->password),
                'role' => $role,
                'role_id' => $findRole->id,
                'api_token' => Str::random(15)
            ]);
        }

        $result = [
            'role' => $role,
            'username' => $username,
            'password' => $password

        ];
        return response()->json($result, 201);
    }

    public function login(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        $inputs = $request->all();

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);

        if ($validator->fails()) {

            return response(['message' => $validator->errors()]);
        } else {

            $findUser = User::where('username', $username)->first();

            if (!$findUser) {
                return response(['message' => 'Username not found'], 404);
            }

            $passwordHash = $findUser->password;

            if (Hash::check($password,  $passwordHash)) {

                return response([
                    'message' => '嗨，好久不見：' . $username . '，您的資料如下：',
                    'data' => $findUser
                ], 200);
            } else {
                return response(['message' => 'Wrong password'], 401);
            }
        }
    }

    public function index(){
        return User::all();
    }
}
