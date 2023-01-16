<?php

namespace App\Http\Controllers;

use App\Models\TipusAllotjament;
use Illuminate\Http\Request;

class TipusAllotjamentController extends Controller
{
        /**
    * @OA\Get(
    * path="/api/tipusallotjament",
    * tags={"Tipus Allotjament"},
    * summary="Mostrar tots els tipus de allotjaments.",
    * @OA\Response(
    * response=200,
    * description="Mostrar tots els tipus de allotjaments."
    * ),
    * @OA\Response(
    * response=400,
    * description="Hi ha un error."
    * ),
    * )
    */
    public function getTipusAllotjaments(){
        $tipusServeis = TipusAllotjament::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipusallotjament/{id}",
     *     tags={"Tipus Allotjament"},
     *     summary="Mostrar un tipus d'allotjament",
     *     @OA\Parameter(
     *         description="Id del tipus d'allotjament",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del tipus d'allotjament.",
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
     *          @OA\Property(property="data",type="string", example="tipus d'allotjament no trobada")
     *           ),
     *     )
     * )
     */
    public function getTipusAllotjament($id){
        $tipusServei = TipusAllotjament::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }
}
