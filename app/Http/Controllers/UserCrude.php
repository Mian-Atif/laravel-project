<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class UserCrude extends Controller
{

      function updateUser(Request $request){
       $token=$request->bearerToken();
       $token_data=(new UserController)->decodeToken($token);
       $email=$token_data->data->email;
       $data=User::where("email",$email)->first();
       $data->name     =$request->name;
       $data->phone_no =$request->phone_no;
       $data->profile  =$request->profile;
       $result=$data->save();
       if($result){
        return['result'=>"data is successfuly updated"];
       }
       else{
        return['result'=>"data is successfuly updated"];
       }
    }
    function searchUser(Request $request){
        $token=$request->bearerToken();
        if(isset($token)){
        $email=$request->email;
        return User::where("email",$email)->get();
        }
        else{
        return['token'=>"token expire"];
        }

    }
    function upLoadFile(Request $request){
        $token=$request->bearerToken();
        if(isset($token)){
        $result=$request->file('file')->store('api doc');
        return ['result'=>$result,'data'=>"file is successfulyy added"];
        }
        else{
        return['token'=>"token expire"];
        }

    }
}
