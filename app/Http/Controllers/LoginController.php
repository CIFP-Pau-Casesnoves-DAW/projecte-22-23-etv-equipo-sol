<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('Email', $request->input('Email'))->first();
        if($user && Hash::check($request->input('Password'), $user->password)){
            $apikey = base64_encode(Str::random(40));
            $user["api_token"]=$apikey;
            $user->save();
            return response()->json(['status' => 'Login OK','result' => $apikey]);
        }else{
            return response()->json(['status' => 'fail'],401);
        }
    }
}
