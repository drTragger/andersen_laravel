<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserRegistration;
use App\Http\Controllers\API\CommentController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [UserRegistration::class, 'register']);

Route::post('/login', [UserRegistration::class, 'login']);

Route::post('/password-reset', [UserRegistration::class, 'resetPassword']);

Route::post('/new-password', [UserRegistration::class, 'setNewPassword']);



Route::group(['middleware' => 'auth:api'], function () {
    Route::put('/users/{id}', [UserRegistration::class, 'updateUserData']);
    Route::get('/users/{id}', [UserRegistration::class, 'getUser']);
    Route::get('/users', [UserRegistration::class, 'getUsers']);
    Route::delete('/users/{id}', [UserRegistration::class, 'deleteUser']);
    Route::post('/create', [CommentController::class, 'create']);
});
