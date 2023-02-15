<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $image = $request->file('image');
        if($request->file('image')){
            $fileName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('/'), $fileName);
            $photoUrl = url('/'.$fileName);
        }else{
            $photoUrl = '';
        }
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        if($request->remember_me){
            $input = ["email"=>$request->email,"age"=>$request->age,"name"=>$request->name,"password"=>$request->password,"image"=>$photoUrl,"about"=>$request->about,"type"=>$request->type,"height"=>$request->height,"weight"=>$request->weight,"gender"=>$request->gender,'remember_token' => Str::random(60),];
        }else{
            $input = ["email"=>$request->email,"age"=>$request->age,"name"=>$request->name,"password"=>$request->password,"image"=>$photoUrl,"about"=>$request->about,"type"=>$request->type,"height"=>$request->height,"weight"=>$request->weight,"gender"=>$request->gender];
        }
        
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('Chattydate')->plainTextToken;
        $success['name'] = $user->name;
        Auth::user();
        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User registrated successfully'
        ];
        return response()->json($response, 200);
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            session(['user_email' => $user->email]);
            session(['user_id' => $user->id]);
        $success['token'] = $user->createToken('Chattydate')->plainTextToken;
        $success['name'] = $user->name;
        $success['id'] = $user->id;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User logged in successfully'
        ];
        return response()->json($response, 200);
        }else{
            $response = [
                'success' => false,
                'message' => 'Unauthorized',
            ];
            return response()->json($response, 400);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        $response = [
            'success' => true,
            'message' => 'User logged out  successfully'
        ];
        return response()->json($response, 200);
    }
}
