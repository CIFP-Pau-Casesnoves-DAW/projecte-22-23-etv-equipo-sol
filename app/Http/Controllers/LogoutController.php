<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LogoutController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/logout",
     *    tags={"Logout"},
     *    summary="Logout",
     *    description="Fa logout del actual usuari, eliminant el seu token de la base de dades",
     *    security={{"bearerAuth":{}}},
     *    @OA\Response(
     *         response=200,
     *         description="Success"
     *    )
     *  )
     */
    public function logout(Request $request)
    {
        $request->DadesUsuari->Token = null;

        if ($request->DadesUsuari->save()) {
            return response()->json(['Status' => 'Success','Result' => ""], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error fent logout'], 400);
        }
    }
}
