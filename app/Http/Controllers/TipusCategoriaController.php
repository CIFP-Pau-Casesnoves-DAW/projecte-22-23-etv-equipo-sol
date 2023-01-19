<?php

namespace App\Http\Controllers;

use App\Models\TipusCategoria;

class TipusCategoriaController extends Controller
{

    /**
    * @OA\Get(
    * path="/api/tipuscategoria",
    * tags={"Tipus Categoria"},
    * summary="Mostrar tots els tipus de categoria.",
    * @OA\Response(
    * response=200,
    * description="Mostrar tots els tipus de categoria."
    * ),
    * @OA\Response(
    * response=400,
    * description="Hi ha un error."
    * ),
    * )
    */
    public function getTipusCategories(){
        $tipusServeis = TipusCategoria::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipuscategoria/{id}",
     *     tags={"Tipus Categoria"},
     *     summary="Mostrar un tipus de categoria",
     *     @OA\Parameter(
     *         description="Id del tipus de categoria",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del tipus de categoria.",
     *          @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="200"),
     *          @OA\Property(property="data",type="object")
     *           ),    
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Error"),
     *          @OA\Property(property="data",type="string", example="tipus de categoria no trobada")
     *           ),
     *     )
     * )
     */
    public function getTipusCategoria($id){
        $tipusServei = TipusCategoria::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }
}
