<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Models\Messages;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
   public function makeChat(Request $request){
    $user = $request->user();
    $user_second_id = $request->user_second_id;
    $input = ["user_id" => $user->id, "second_user_id" => $user_second_id, "messages" => []];
        if($input){
            $messages = Messages::create($input);
            $responce = [
                'status' => true,
                'message' => $messages
            ];
            return response($responce, 200);
        }else{
            $responce = [
                'status' => false,
                'message' => 'cant create chat'
            ];
            return response($responce, 400);
        }
   }

   public function sendMessages(Request $request, $id){
        $chat = Messages::find($id);
        if($chat->messages !== null){
            $chat_messages = $chat->messages;
        }else{
            $chat_messages = [];
        }
        array_push($chat_messages, $request->message);
        $chat->messages = $chat_messages;
        
        if(is_null($chat)){
            return response()->json([
                "message"=> 'chat not found'
            ], 404);
        }

        $result = $chat->save();
        if($result)
        {
            return response()->json([
                "data" => $chat
            ],200);
            
        }else{
            return response()->json([
                "result" => "false"
            ]);
        }
   }


   public function chats(Request $request){
        $chats = DB::table('messages')->where("user_id", $request->user()->id)->orWhere("second_user_id", $request->user()->id)->get();
        if($chats){
            $response = [
                "status" => true,
                "chats" => $chats
            ];
            return response($response, 200);
        }
   }
   public function chatMessage(Request $request){
    event(new ChatMessageEvent($request->message));
    $response = [
        "message" => $request->message,
        "user" => [$request->user()->id,$request->user()->name],
        "date" => date("Y/m/d/") . date("h:i")
    ];
    return response($response, 200);
   }
}
