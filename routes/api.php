<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCrude;
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
Route::post("signin",[UserController::class,"signIn"]);
Route::put("updateuser",[UserCrude::class,"updateUser"]);
Route::get("searchuser/{email}",[UserCrude::class,"searchUser"]);
Route::post("uploadfile",[UserCrude::class,"upLoadFile"]);
