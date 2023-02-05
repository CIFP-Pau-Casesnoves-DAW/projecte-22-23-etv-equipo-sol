<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/login",
     *    tags={"Login"},
     *    summary="Login per obtenir token de autoritzacio",
     *    description="Utilitza el login per tal de entrar amb les teves credencials i obtenir el token de autoritzacio",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="CorreuElectronic", type="string", format="string", example="joan@gmail.com"),
     *           @OA\Property(property="Contrasenya", type="string", format="string", example="AghT6j7!saL*")
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="string", example="apiKey")
     *         ),
     *    ),
     *    @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result", type="string", example="Les teves credencials son incorrectes")
     *         ),
     *     )
     *  )
     */
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
