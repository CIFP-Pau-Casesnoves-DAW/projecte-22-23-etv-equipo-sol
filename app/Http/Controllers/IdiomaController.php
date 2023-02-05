<?php

namespace App\Http\Controllers;

use App\Models\Idioma;

class IdiomaController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/idioma",
     *      tags={"Idiomes"},
     *      summary="Mostrar tots els idiomes.",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *          )
     *      )
     * )
     */
    public function getIdiomes(){
        $idiomes = Idioma::all();
        return response()->json(["Status" => "Success","Result" => $idiomes], 200);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/idioma/{id}",
     *     tags={"Idiomes"},
     *     summary="Mostrar l'idioma al qual correspon la id passada per a la url",
     *     @OA\Parameter(
     *         description="Id del idioma",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *          )
     *     ),
     * )
     */
    public function getIdioma($id){
        $idioma = Idioma::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $idioma], 200);
    }
}
