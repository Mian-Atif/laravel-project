<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\User;
use App\Models\friend;
use App\Models\comment;

class UserPost extends Controller
{
    //
    function addPost(Request $request){

        $token=$request->bearerToken();
        if(!empty($token)){
        $token=(new UserController)->decodeToken($token);
        $id=$token->data->id;
        if(!empty($id)){
        $user = User::find($id);
        $post = new Post;
        $post->body = $request->body;
        $post->visibility = $request->visibility;
        $user = $user->posts()->save($post);

        return response()->json(['data'=>"your post added successfuly"],200);
        }
        else{
            return response()->json(['data'=>"there is some server error please try again"],500);
        }
        }
        else
        return response()->json(['data'=>"token expire"]);

     }

function addFriend(Request $request){

    $token=$request->bearerToken();
    if(!empty($token)){
    $token=(new UserController)->decodeToken($token);
    $id=$token->data->id;

    $email=$request->email;
    $user = User::where('email',$email)->first();
    $user_id=$user['id'];

    if($user_id==$id){
    if(!empty($user_id)){
    $user = User::find($user_id);
    $friend = new friend;
    $friend->friend_id =$user_id;
    $user = $user->friends()->save($friend);
    return response()->json(['data'=>"friend added successfuly"],200);
    }
    else{
        return response()->json(['data'=>"user does not exit"],404);
    }
    }
    else{
        return response()->json(['data'=>"there is some server error"],500);
    }

    }
    else{
    return response()->json(['data'=>"token expire please login again"],202);
    }
 }


function addComment(Request $request){
    $token=$request->bearerToken();
    $post=$request->id;
    if(!empty($token)){
    $token=(new UserController)->decodeToken($token);
    $token_id=$token->data->id;
    if(!empty($token_id)){
    $user = User::find($token_id)->first();
    $post =Post::find($post)->first();
    $comment = new comment();
    $comment->comment_body =$request->comment_body;
    // $user = $user->comments()->save($comment);
        $comment->users()->associate($user);
        $comment->posts()->associate($post);
        $comment=$comment->save();
        // $comment=$comment->create();
    return response()->json(['data'=>"comment added successfuly"],200);
    }
    else{
        return response()->json(['data'=>"user does not exit"],404);
    }
    }
    else{
    return response()->json(['data'=>"token expire please login again"],202);
    }



}


}
