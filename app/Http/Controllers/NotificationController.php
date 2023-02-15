<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class NotificationController extends Controller
{
    public function getNotifications(Request $request){
        
        $user = $request->user();
        $notifications = DB::table('notifications')->where('user_id', $user->id)->get();
            if ($notifications) {
                $responce = [
                    'status' => true,
                    'notifications' => $notifications
                ];
                return response($responce, 200);
            
        }else{
            $responce = [
                'status' => true,
                'message' => 'error'
            ];
            return response($responce, 400);
        }
    }
}
