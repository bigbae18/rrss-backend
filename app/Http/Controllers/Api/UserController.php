<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class UserController extends Controller
{
    /*
    *  TODO:
    *  getUserById()
    *  getUsers()
    *  deleteUserById()
    *  getUserFollowers()
    */

    public function getUserById($id) {

        $validator = Validator::make([$id], ['required|int']);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get();

            return response()->json([
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'El usuario no existe'
            ], 404);
        }
    }

    public function deleteUserById(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|int']);

        $id = $request->get('id');

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        if (User::where('id', $id)->exists()) {

            User::where('id', $id)->delete();
            if (!User::where('id', $id)->exists()) {
                return response()->json([
                    'message' => 'The user with ID ' . $id . ' has been deleted.'
                ]);
            } else {
                return response()->json([
                    "message" => "User didn't deleted successfuly"
                ]);
            }
        } else {
            return response()->json([
                "message" => "The user specified doesn't exist"
            ]);
        }
    }
}