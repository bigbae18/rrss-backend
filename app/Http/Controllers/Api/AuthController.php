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

        if (User::where('username', $request->get('username'))->exists()) {
            $input = $request->all();
            $input["password"] = Hash::make($request->get('password'));

            $user = User::create($input);

            $token = $user->createToken($user["username"])->accessToken;

            return response()
                    ->json(
                        [
                            'token' => $token,
                            'user' => $user
                        ], 200);
        } else {
            return response()->json([
                "message" => "The username already exists"
            ], 400);
        }
        

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
            $user = User::where('username', $request->get('username'))->get();
            $user_id = User::where('username', $request->get('username'))->value('id');
            // Obtiene token id si existe / sino null
            $token_id = (DB::table('oauth_access_tokens')->where('user_id', $user_id)->exists() ? DB::table('oauth_access_tokens')->where('user_id', $user_id)->value('id') : null);

            if (Hash::check($credentials["password"], User::where('username', $request->get('username'))->value('password'))) {

                return response()->json(
                    [
                        'token_id' => $token_id,
                        'user' => $user
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