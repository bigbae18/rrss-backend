<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class FollowersController extends Controller
{
    public function follow(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'follower_id' => 'required|int',
            'following_id' => 'required|int'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ]);
        }
        $follower_id = $request->get('follower_id');
        $following_id = $request->get('following_id');
        
        if (User::where('id', $following_id)->exists()) {
            $following_user = User::where('id', $following_id)->get();
            $follower_user = User::where('id', $follower_id)->get();

            $follower_user->follower()->attach($following_user);

        } else {
            return response()->json([
                "message" => "User to follow doesn't exists"
            ]);
        }
    }

    public function unfollow(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'unfollower_id' => 'required|int',
            'unfollowing_id' => 'required|int'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ]);
        }
        $unfollower_id = $request->get('unfollower_id');
        $unfollowing_id = $request->get('unfollowing_id');
        
        if (User::where('id', $unfollowing_id)->exists()) {
            $unfollowing_user = User::where('id', $unfollowing_id)->get();
            $unfollower_user = User::where('id', $unfollower_id)->get();

            $unfollower_user->follower()->dettach($unfollowing_user);

        } else {
            return response()->json([
                "message" => "User to unfollow doesn't exists"
            ]);
        }
    }

    public function getFollowing($id) {

        $validator = Validator::make([$id], ['required|int']);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ]);
        }

        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get();
            $followers_count = 0;
            $followers_array = [];
            $followers = $user->followers()->get();

            foreach($followers as $follower) {
                $follower_count++;
                $followers_array = [...$followers_array, $follower];
            }
            return response()->json([
                'user' => $user,
                'followers' => $followers,
                'followers_count' => $follower_count,
                'followers_array' => $followers_array
            ]);
        } else {
            return response()->json([
                'message' => "User doesn't exist"
            ]);
        }
    }

    public function getFollowers($id) {

        $validator = Validator::make([$id], ['required|int']);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ]);
        }

        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get();
            $followers_count = 0;
            $followers_array = [];
            $followers = $user->followers()->get();

            foreach($followers as $follower) {
                $follower_count++;
                $followers_array = [...$followers_array, $follower];
            }
            return response()->json([
                'user' => $user,
                'followers' => $followers,
                'followers_count' => $follower_count,
                'followers_array' => $followers_array
            ]);
        } else {
            return response()->json([
                'message' => "User doesn't exist"
            ]);
        }
    }
}
