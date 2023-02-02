<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $usuari = Usuari::where("CorreuElectronic",$request->input("CorreuElectronic"))->first();
        //Utilitzar es comentat si hasheam ses contrasenyes
        //if($usuari && Hash::check($request->input("Contrasenya"), $usuari->Contrasenya)){
        if($usuari && $request->input("Contrasenya") == $usuari->Contrasenya){
            $apiKey = base64_encode(Str::random(40));
            $usuari["Token"]=$apiKey;
            $usuari->save();
            return response()->json(["Status" => "Success","Result" => $apiKey]);
        }else{
            return response()->json(["Status" => "Error","Result" => "Les teves credencials son incorrectes"],401);
        }
    }
}
