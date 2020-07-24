<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

class AuthController extends Controller {


    public function register (Request $request) {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()
                    ->json(["error" => $validator->errors()], 422);
        };

        $input = $request->all();
        $input["password"] = Hash::make($request->get('password'));
        //bcrypt($request->get('password'));

        $user = User::create($input);

        $token = $user->createToken('SocialGeeks')->accessToken;

        return response()
                ->json(
                    [
                        'token' => $token,
                        'user' => $user
                    ], 200
                );

    }

    public function login(Request $request) {
        
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

        
        

        if (User::where('username', $request->get('username'))->exists()) {
            
            $credentials = $request->only('username', 'password');
            $user = User::where('username', '=', $request->get('username'))->get();

            if (Hash::check($credentials["password"], User::where('username', $request->get('username'))->value('password'))) {

                return response()->json(
                    [
                        'user' => $user,
                        'authenticated' => (Auth::attempt($credentials) ? true : false)
                    ], 202
                );
            } else {
                return response()->json(
                    [
                        'message' => 'Las contraseñas no coinciden'
                    ], 400
                );
            }
        } else {
            return response()->json(
                [
                    'message' => 'El usuario no existe'
                ], 400
            );
        }
    }
}