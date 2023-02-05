<?php

namespace App\Http\Controllers;

use App\Models\TipusVacances;
use Illuminate\Http\Request;

class TipusVacancesController extends Controller
{
    /**
    * @OA\Get(
    * path="/api/tipusvacances",
    * tags={"Tipus Vacances"},
    * summary="Mostrar tots els tipus de vacances.",
    * @OA\Response(
    * response=200,
    * description="Mostrar tots els tipus de vacances.",
    * @OA\JsonContent(
    * @OA\Property(property="Status", type="string", example="Success"),
    * @OA\Property(property="Result",type="object")
    * ),
    * ),
    * )
    */
    public function getTipusVacances(){
        $tipusVacances = TipusVacances::all();
        return response()->json(["Status" => "Success","Result" => $tipusVacances], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipusvacances/{id}",
     *     tags={"Tipus Vacances"},
     *     summary="Mostrar un tipus de vacances",
     *     @OA\Parameter(
     *         description="Id del tipus de vacances",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del tipus de vacances.",
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
     *          @OA\Property(property="Result",type="string", example="Tipus de vacances no trobada")
     *           ),
     *     )
     * )
     */
    public function getTipusVacance($id){
        $tipusVacances = TipusVacances::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusVacances], 200);
    }
}
