<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserCrude extends Controller
{

      function updateUser(Request $request){
       $token=$request->bearerToken();
       $token=(new UserController)->decodeToken($token);
       $email=$token->data->email;
       $data = User::where("email",$email)->first();
       $data->name    =$request->name;
       $data->phone_no=$request->phone_no;
       $data->profile =$request->profile;
       $result        =$data->save();
       if($result){
        return response()->json(['result'=>"data is successfuly updated"]);
       }
       else{
        return response()->json(['result'=>"data is not updated"]);
       }
    }
    function searchUser(Request $request){
        $token=$request->bearerToken();
        if(isset($token)){
        $email=$request->email;
        $result=User::where("email",$email)->get();
        return response()->json(['data'=>$result]);
        }
        else{
            return response()->json(['token'=>"token expire"]);
        }

    }
    function upLoadFile(Request $request){
        $token=$request->bearerToken();
        $token=(new UserController)->decodeToken($token);
        $email=$token->data->email;
        if(isset($token)){
        $user = DB::table('users')->where('email',$email)->first();
        $result=$request->file('file')->store('api doc');
        User::where('email', $user->email)
           ->update(['profile' =>$result]);
        return response()->json(['result'=>$result,'data'=>"file is successfulyy added"]);
        }
        else{
        return response()->json(['token'=>"token expire"]);
        }

    }
    function forGetPassword(Request $request){

        $user = DB::table('users')->where('email',$request->email)->first();
            //dd($user);
        if($request->favorite_animal===$user->favorite_animal){
            $new_password= hash::make($request->password);
            User::where('email', $user->email)
           ->update(['password' => $new_password]);

        return response()->json(['data'=>"new password save successfuly"]);
        }
        else{
            return response()->json(['data'=>"credentials is invailed"]);
        }

    }
}
