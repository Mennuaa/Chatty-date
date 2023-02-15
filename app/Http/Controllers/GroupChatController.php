<?php

namespace App\Http\Controllers;

use App\Events\SendGroupMessageEvent;
use App\Models\GroupChat;
use Illuminate\Http\Request;
use App\Models\Messages;
use Illuminate\Support\Facades\DB;

class GroupChatController extends Controller
{
    public function makeChat(Request $request){
        $user = $request->user();
        $input = ["users" => [$user->id], "messages" => [], "name" => $request->name];
            if($input){
                $messages = GroupChat::create($input);
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
            $chat = GroupChat::find($id);
            $chat_messages = $chat->messages;
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

       public function enter(Request $request,$id){
        $user = $request->user();
        $group = GroupChat::find($id);
        $users = $group->users;
        $group->users = [...$users , $user->id];

        $result = $group->save();
        if($result)
        {
            return response()->json([
                "data" => $group
            ],200);
            
        }else{
            return response()->json([
                "result" => "false"
            ]);
        }
       }

       public function chats(Request $request){
        $chats = DB::table('group_chats')->get();
        foreach($chats as $chat){
            for ($i=0; $i < strlen($chat->users); $i++) { 
                if($chat->users[$i]!== "[" && $chat->users[$i]!== "]"  && $chat->users[$i]!== " " && $chat->users[$i]!== ","){
                    if($chat->users[$i] == $request->user()->id){
                        $response = [
                            "status" => true,
                            "chats" => $chats
                        ];
                    }else{
                        $response = [
                            "status" => false,
                            "chats" => "User dont have any group chats"
                        ];
                    }
                }
            }
        }
        return response($response, 200);

        
   }

   public function chatMessage(Request $request){
    event(new SendGroupMessageEvent($request->message));
    $response = [
        "message" => $request->message,
        "user" => [$request->user()->id,$request->user()->name],
        "date" => date("Y/m/d/") . date("h:i")
    ];
    return response($response, 200);
   }
}
