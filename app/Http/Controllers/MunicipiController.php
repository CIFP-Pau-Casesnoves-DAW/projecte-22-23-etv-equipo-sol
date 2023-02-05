<?php

namespace App\Http\Controllers;

use App\Models\Municipi;
use Illuminate\Http\Request;

class MunicipiController extends Controller
{
    /**
    * @OA\Get(
    * path="/api/municipis",
    * tags={"Municipis"},
    * summary="Mostrar tots els municipis.",
    * @OA\Response(
    * response=200,
    * description="Mostrar tots els municipis.",
    * @OA\JsonContent(
    * @OA\Property(property="Status", type="string", example="Success"),
    * @OA\Property(property="Result",type="object")
    * ),
    * ),
    * )
    */
    public function getMunicipis(){
        $municipis = Municipi::all();
        return response()->json(["Status" => "Success","Result" => $municipis], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/municipi/{id}",
     *     tags={"Municipi"},
     *     summary="Mostrar un municipi",
     *     @OA\Parameter(
     *         description="Id del municipi",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del municipi.",
     *          @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Success"),
     *          @OA\Property(property="Result",type="object")
     *           ),
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Error"),
     *          @OA\Property(property="Result",type="string", example="Tipus de municipi no trobat")
     *           ),
     *     )
     * )
     */
    public function getMunicipi($id){
        $municipi = Municipi::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $municipi], 200);
    }
}
