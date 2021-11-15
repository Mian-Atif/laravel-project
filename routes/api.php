<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCrude;
use App\Http\Controllers\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//-Route::post("logout",[UserController::class,"logOut"]);
Route::post("signup",[UserController::class,"signUp"]);
Route::get("emailConfirmation/{email}",[UserController::class,"emailConfirmation"]);
Route::post("signin",[UserController::class,"signIn"]);
Route::post("logout",[UserController::class,"logOut"]);


Route::post("updateuser",[UserCrude::class,"updateUser"]);
Route::post("searchuser",[UserCrude::class,"searchUser"]);
Route::post("uploadfile",[UserCrude::class,"upLoadFile"]);
Route::post("forgetpassword",[UserCrude::class,"forGetPassword"]);

Route::post("addpost",[UserPost::class,"addPost"]);
Route::post("addfriend",[UserPost::class,"addFriend"]);
Route::post("addcomment",[UserPost::class,"addComment"]);




