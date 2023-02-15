<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    public function upload(Request $request){
        if($request->file('image')){
            $fileName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('/'), $fileName);
            $photoUrl = url('/'.$fileName);
            $response = [
                "status" => true,
                "name" => $photoUrl
            ];
            return response($response, 200);
        }else{
            $photoUrl = '';
            $response = [
                "status" => true,
                "name" => $photoUrl
            ];
            return response($response, 200);
        }
    }
}
