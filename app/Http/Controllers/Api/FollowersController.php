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
            $following_id = $request->get('following_id');
            $validator = Validator::make([$following_id], [
                'required|int'
                ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ],400);
            }
            $user = Auth::user();
            $following_user = User::where('id', $following_id)->exists();
            if ($following_user) {
                $user->following()->attach([$following_id]);
                return response()->json([
                    'message' => 'Followed succesfuly'
                ], 200);
            } else {
                return response()->json([
                    'message' => "User with ID requested doesn't exist"
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function unfollow(Request $request) {
        try {
            $unfollowing_id = $request->get('unfollowing_id');
            $validator = Validator::make([$unfollowing_id], [
                'required|int'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ],400);
            }
            $user = Auth::user();
            $unfollowing_user = User::where('id', $unfollowing_id)->exists();
            $isFollowing = $user->following()->find($unfollowing_id);
            if (!$unfollowing_user) {
                return response()->json([
                    'message' => "User with that ID doesn't exist"
                ], 400);
            } elseif ($isFollowing === null) {
                return response()->json([
                    'message' => "User not following that user"
                ], 400);
            } else {
                $user->following()->detach($unfollowing_id);
                return response()->json([
                    'message' => 'Unfollowed succesfuly'
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong unfollowing',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getFollowers(int $id) {
        try {
            $validator = Validator::make([$id], ['required|int']);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ],400);
            }
            $user = User::find($id);
            if ($user === null) {
                return response()->json([
                    'message' => 'User not exists'
                ], 400);
            }
            $follower_count = 0;
            foreach($user->followers as $follower_user) {
                $follower_count++;
            }
            $user->followers_count = $follower_count;
            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong getting followers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getFollowing(int $id) {
        try{
            $validator = Validator::make([$id], ['required|int']);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ], 400);
            }
            $user = User::find($id);
            if ($user === null) {
                return response()->json([
                    'message' => 'User not exists'
                ], 400);
            }
            $following_count = 0;
            foreach($user->following as $following_user) {
                $following_count++;
            }
            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong getting following',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
