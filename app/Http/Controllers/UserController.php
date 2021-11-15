<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Controllers\delete;
use Exception;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    function signUp(Request $req){
        $date=date('Y-m-d h:i:s');
        $result=DB::table('users')->insert([
            'name'           =>  $req->name,
            'email'          => $req->email,
            'password'       => hash::make($req->password),
            'phone_no'       => $req->phone_no,
            'profile'        => $req->profile,
            'favorite_animal'=> $req->favorite_animal,
            'created_at'     =>$date,
        ]);

        if($result){
            // data creation for email
            $details['link']=url('api/emailConfirmation/'.$req->email);
            $details['user_name']= $req->name;
            $details['email']=$req->email;

            //send verification mail
            Mail::to($req->email)->send(new \App\Mail\EmailVerification($details));

            return response()->json(["result"=>"user is successfully signup"]);
        }
        else{
            return response()->json(["result"=>"opration faild"]);
        }
    }
    public function emailConfirmation($email)
    {
        $user = User::where('email',$email)->first();
        if (!empty($user['id'])) {
            if (empty($user['email_verified_at'])) {
                $user->email_verified_at=date('Y-m-d h:i:s');
                try{
                    $user->update();
                    return response()->json(['data'=>"Your Email Verified Sucessfully!!!"]);
                }catch(Exception $ex){
                    return response()->json(['Error'=>"Something Went Wrong".$ex->getMessage()]);
                }
            }else{
                return response()->json(['data'=>"Already Verified"],202);
            }
        }else{
            return response()->json(['data'=>"Linked Expired"],404);
        }
    }
    function createToken($user){
        $iss = "localhost";
        $iat = time();
        $nbf = time();
        // $exp = $iat +4550;
        $aud = 'app user';

        $secret_key = "owt125";
        $payload_info=array(
            "iss"=> $iss,
            "iat"=> $iat,
            "nbf"=> $nbf,
           // "exp"=> $exp,
            "aud"=> $aud,
            "data"=>$user
        );
        $jwt = JWT::encode($payload_info, $secret_key,'HS512');

        return $jwt;

    }
    public function decodeToken($token){
        $decoded_data = JWT::decode($token, new key("owt125","HS512"));
        return $decoded_data;
    }
    function signIn(Request $req){
        $user = DB::table('users')->where('email',$req->email)->first();
        //dd($user->remember_token);
        if (Hash::check($req->password,$user->password))
        {
            if(($user->remember_token==null)){
         $jwt=$this->createToken($user);
         //save token in
         User::where('email', $user->email)
          ->update(['remember_token' => $jwt]);
            }
            else{
                return response()->json(['data'=>"user alreday sign"]);

            }

         return response()->json(['token'=>$jwt]);
        }
        else{
            return response()->json(['message'=>"email and password not valied"]);
        }

    }

    function logOut(Request $request){
        $token=$request->bearerToken();
        $token=$this->decodeToken($token);
        $email=$token->data->email;
        User::where('email', $email)
        ->update(['remember_token' => ""]);
        $remember_token=$token->data->remember_token;
        if($remember_token==null){

            return response()->json(['message'=>"you are successfully logout"]);
        }
        else{
            return response()->json(['message'=>"there is some problem in logout"]);


        }
       }

}
