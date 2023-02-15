<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRecomendations extends Controller
{
    public function recomendations(Request $request, $id){
        $user = $request->user();
        function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
            $theta = $longitude1 - $longitude2;
            $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
            $miles = acos($miles);
            $miles = rad2deg($miles);
            $miles = $miles * 60 * 1.1515;
            $feet = $miles * 5280;
            $yards = $feet / 3;
            $kilometers = $miles * 1.609344;
            $meters = $kilometers * 1000;
            return $kilometers; 
        }
            $user_type_first = $user->type['age'][0] . $user->type['age'][1];
            $int_user_type_first = (int)$user_type_first;
            $user_type_second = $user->type['age'][3] . $user->type['age'][4];
            $int_user_type_second = (int)$user_type_second;
            $obshi = $int_user_type_second - $int_user_type_first;
            $recomendation = [];
            for ($i=$obshi; $i >- 0 ; $i--) { 
               if(DB::table('users')->where("age", $int_user_type_first + $i)->exists()){
                $recomendation = DB::table('users')->where("age", $int_user_type_first + $i)->get();
                    if($user->canceled_users ){
                        foreach($user->canceled_users as $canc){
                            if($recomendation[0]->id != $canc && $recomendation[0]->id != $user->id){
        
                        echo($recomendation);
                    }
                        }
                    }else{
                        if($recomendation[0]->id != $user->id){
                            echo($recomendation);
                        }
                    }
               }
            }
             return    getDistanceBetweenPointsNew($user->lat, $user->long, $recomendation[0]->lat, $recomendation[0]->long);
            
            
    }
}
