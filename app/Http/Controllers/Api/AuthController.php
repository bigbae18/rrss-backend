<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use Validator;

class AuthController extends Controller {


    public function register (Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'string|required',
                'email' => 'string|required|email',
                'password' => 'string|required',
                'confirm_password' => 'string|required|same:password'
            ]);
            if ($validator->fails()) {
                return response()
                        ->json(["message" => $validator->errors()], 422);
            };
            if (!User::where('username', $request->get('username'))->exists()) {
                $input = $request->all();
                $input["password"] = Hash::make($request->get('password'));
                $user = User::create($input);
                return response()->json([
                            'user' => $user,
                            'message' => 'Registered successfuly'
                        ], 200);
            } else {
                return response()->json([
                    "message" => "The username already exists"
                ], 400);
            }
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There was an error trying to register user',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(
                [
                 'error' => $validator->errors()
               ], 422);
            }

            $credentials = $request->only('username', 'password');

            if (!User::where('username', $credentials["username"])->exists()) {
                return response()->json([
                    'message' => "User doesn't exists"
                ], 400);
            } elseif (!Hash::check($credentials["password"], User::where('username', $credentials["username"])->value("password"))) {
                return response()->json([
                    'message' => "Password doesn't match"
                ], 400);
            } else {
                $user_id = User::where('username', $credentials["username"])->value('id');
                Auth::loginUsingId($user_id);
                $user = Auth::user();
                $token = $user->createToken($credentials["username"])->accessToken;
                $user->token = $token;
                return response()->json([
                    'token' => $token,
                    'user' => $user,
                    'authorized' => Auth::check()
                ],200);
            }
        } catch(\Exception $e) {
            return response()->json([
                    "message" => 'Error trying to login user',
                    "error" => $e->getMessage()
                ], 500);
        }
    }
}