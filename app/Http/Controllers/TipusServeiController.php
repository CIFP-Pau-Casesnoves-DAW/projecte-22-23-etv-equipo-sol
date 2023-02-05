<?php

namespace App\Http\Controllers;

use App\Models\TipusServei;

class TipusServeiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/tipusservei",
     *      tags={"Tipus Servei"},
     *      summary="Mostrar tots els tipus de serveis.",
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
    public function getTipusServeis(){
        $tipusServeis = TipusServei::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipusservei/{id}",
     *     tags={"Tipus Servei"},
     *     summary="Mostrar el tipus de servei al qual correspon la id passada per a la url",
     *     @OA\Parameter(
     *         description="Id del comentari",
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
    public function getTipusServei($id){
        $tipusServei = TipusServei::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }
}
