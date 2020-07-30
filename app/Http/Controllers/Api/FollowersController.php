<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class FollowersController extends Controller
{

    public function follow(Request $request) {
        try {
            $requested_id = $request->get('following_id');
            $validator = Validator::make([$requested_id], [
                'required|int'
                ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ],400);
            }
            $user = Auth::user();
            $following_user = User::where('id', $requested_id)->get();
            if ($following_user !== null) {
                $user->following->attach($following_user);
                return response()->json([
                    'message' => 'Followed succesfuly'
                ],200);
            } else {
                return response()->json([
                    'message' => "User with ID requested doesn't exist"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    * REMAKE
    */
    // public function unfollow(Request $request) {
        
    //     $validator = Validator::make($request->all(), [
    //         'unfollowing_id' => 'required|int'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => $validator->errors()
    //         ], 400);
    //     }
    //     $unfollowing_id = $request->get('unfollowing_id');

    //     if (User::where('id', $unfollowing_id)->exists()) {
    //         $unfollowing_user = User::where('id', $unfollowing_id)->get();
    //         $unfollower_user = Auth::user();
    //         $unfollower_user->following()->dettach($unfolling_user);
    //         return response()->json([
    //             "message" => "User unfollow"
    //         ],200);
    //     } else {
    //         return response()->json([
    //             "message" => "User to unfollow doesn't exists"
    //         ], 400);
    //     }
    // }
    // public function getFollowing() {
    //     $user = Auth::user();
    //     dd($user);
    //     if ($user != null) {
    //         $following_count = 0;
    //         $following_array = [];
    //         $followings = $user->following()->get();
    //         foreach($followings as $following) {
    //             $following_count++;
    //             $following_array = [...$following_array, $following];
    //         }
    //         return response()->json([
    //             'user' => $user,
    //             'following' => $following,
    //             'following_count' => $following_count,
    //             'following_array' => $following_array
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => "Can't find user by Id"
    //         ], 400);
    //     }
    // }

    // public function getFollowers($id) {

    //     $validator = Validator::make([$id], ['required|int']);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => $validator->errors()
    //         ], 400);
    //     }

    //     $user = Auth::retrieveById($id);

    //     if ($user != null) {
    //         $followers_count = 0;
    //         $followers_array = [];
    //         $followers = $user->followers()->get();

    //         foreach($followers as $follower) {
    //             $follower_count++;
    //             $followers_array = [...$followers_array, $follower];
    //         }
    //         return response()->json([
    //             'user' => $user,
    //             'followers' => $followers,
    //             'followers_count' => $follower_count,
    //             'followers_array' => $followers_array
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => "User doesn't exist"
    //         ], 400);
    //     }
    // }
}
