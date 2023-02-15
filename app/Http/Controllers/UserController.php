<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function getUser(Request $request , $id){
        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                "message"=> 'user not found'
            ], 404);
        }
        if($user){
            $response = [
                "status" => true,
                "user" => $user
            ];
            return response($response, 200);
        }
    }

    public function change(Request $request, $id){
        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                "message"=> 'user not found'
            ], 404);
        }
        if($user->id == $request->user()->id){
            $user->update($request->all());
            return response($user, 200);
        }
    }

    public function cancelUser(Request $request){
        $user = $request->user();
        $canceled = $user->canceled_users;
            $user->canceled_users = [...$canceled, $request->user_id];
        
        $result = $user->save();
        if($result)
        {
            return response()->json([
                "data" => $user->canceled_users
            ],200);
            
        }else{
            return response()->json([
                "result" => "false"
            ]);
        }
    }
}
