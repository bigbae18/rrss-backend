<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// AUTH ROUTES

Route::prefix('auth')->group(function() {

    // Registro de user
    Route::post('register', 'Api\AuthController@register');

    // Auth + Login de user
    Route::post('login', 'Api\AuthController@login');
});

// USERS ROUTES

Route::prefix('/users')->group(function () {

    // Obtener user por ID
    Route::get('/{id}', 'Api\UserController@getUserById');
    // Obtener Followers
    Route::get('followers', 'Api\FollowersController@getFollowers');
    // Obtener Followings
    Route::get('following', 'Api\FollowersController@getFollowing');


    // Seguir a un user
    Route::post('follow', 'Api\FollowersController@follow');
    // Dejar de seguir a un user
    Route::post('unfollow', 'Api\FollowersController@unfollow');

    // Borrar user por ID
    Route::delete('/delete/{id}', 'Api\UserController@deleteUserById');

});//->middleware('api:auth')


Route::prefix('/posts')->group(function () {

    // Rutas para Posts
    
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
