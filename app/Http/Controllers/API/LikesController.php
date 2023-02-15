<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Likes;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notifications;

class LikesController extends Controller
{
    public function addToLiked(Request $request){
        $user = $request->user();
        $user_liked_id = $request->user_liked_id;
        $input = ["user_id" => $user->id, "user_liked_id" => $user_liked_id];
        if(!$input){
            $response = [
                'success' => false,
                'message' => 'cant like user'
            ];
            return response()->json($response, 400);
        }
        Likes::create($input);
        $notification = ['user_id' => $user_liked_id, "notification"=>"User " . $user->name . " liked you . Check his profile!" ];
        Notifications::create($notification);
        $response = [
            'success' => true,
            'message' => 'user liked'
        ];
        return response()->json($response, 200);
        // session('user_email')
    }
}
