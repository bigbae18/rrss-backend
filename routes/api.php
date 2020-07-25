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

// USERS ROUTES

Route::prefix('/users')->group(function () {

    // Obtener user por ID
    Route::get('/{id}', 'Api\UserController@getUserById');

    // Borrar user por ID
    Route::delete('/delete/{id}', 'Api\UserController@deleteUserById');

    // Registro de user
    Route::post('register', 'Api\AuthController@register');

    // Auth + Login de user
    Route::post('login', 'Api\AuthController@login');
});

Route::prefix('/posts')->group(function () {

    // Rutas para Posts
    
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
