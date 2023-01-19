<?php

namespace App\Http\Middleware;

use App\Models\Usuari;
use Closure;
use Illuminate\Http\Request;

class TokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header("Authorization")) { // Hem rebut el header d’autorització?
            $key = explode(' ',$request->header("Authorization")); // Esperam un token 'Bearer token'
            if (count($key) != 2){
                return response()->json(["Error" => "Acces no autoritzat"], 401); // token incorrecta
            }

            $token=$key[1]; // key[0]->Bearer key[1]->token
            $usuari = Usuari::where("Token", $token)->first();

            if(!empty($usuari)){
                return $next($request); // Usuari trobat. Token correcta. Continuam am la petició
            } else {
                return response()->json(["Error" => "Acces no autoritzat"], 401); // token incorrecta
            }
        } else {
            return response()->json(["Error" => "Token no rebut"], 401);
        }

    }
}
