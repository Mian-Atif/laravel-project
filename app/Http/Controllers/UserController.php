<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Controllers\delete;
use Illuminate\Support\Facades\Mail;
use Exception;
class UserController extends Controller
{
    //
    public function sendMail(){

        $data =['name'=>"atif",'data' => "hollow atif"];
        $user['to']='muhammadatifrizwan@gmail.com';
        Mail::send('mail',$data,function($message) use ($user){
        $message->to($user['to']);
        $message->subject('Welcom to PF');

        });
    }

    function signUp(Request $req){

            $result=new User();
            DB::table('users')->insert([
            'name' =>  $req->name,
            'email' => $req->email,
            'password' => hash::make($req->password),
            'phone_no' => $req->phone_no,
            'profile' => $req->profile,
        ]);

    if($result){
        $this->sendMail();
        return["result"=>"user is successfully signup"];
    }
    else{

        return["result"=>"opration faild"];
    }
        }

    function createToken($user){
        $iss = "localhost";
        $iat = time();
        $nbf = $iat + 10;
        $exp = $iat +4550;
        $aud = 'app user';

        $secret_key = "owt125";
        $payload_info=array(
            "iss"=> $iss,
            "iat"=> $iat,
            "nbf"=> $nbf,
            "exp"=> $exp,
            "aud"=> $aud,
            "data"=>$user
        );
        $jwt = JWT::encode($payload_info, $secret_key,'HS512');
        return $jwt;

    }
    public function decodeToken($token){
        $decoded_data = JWT::decode($token, new key("owt125","HS512"));
        return $decoded_data ;
    }



    function signIn(Request $req){
        $user = DB::table('users')->where('email',$req->email)->first();
        if (Hash::check($req->password,$user->password))
        {
        $jwt=$this->createToken($user);
        return response()->json(['token'=>$jwt]);
        }
        else{
            return response()->json(['message'=>"email and password not valied"]);
        }

    }

    // public function logOut(Request $request){
    //     $token=$request->bearerToken();
    //    if(isset($token)){
    //        $token->delete();
    //    }
    // }

 }
