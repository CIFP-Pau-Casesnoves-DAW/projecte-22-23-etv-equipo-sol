<?php

namespace App\Http\Controllers;

use App\Models\EstatsReserva;
use App\Http\Controllers\Controller;



class EstatsReservaController extends Controller
{
    //GET de tot
    /**
     * @OA\Get(
     * path="/api/estatsreserva",
     * tags={"EstatsReserva"},
     * summary="Mostrar tots els estats de reserva.",
     *     @OA\Response(
     *         response=200,
     *         description="Informació del estat de la reserva.",
     *          @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Success"),
     *          @OA\Property(property="Result",type="object")
     *           ),
     *      ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *     )
     * )
     */
    public function getEstatsReserves()
    {
        $estatsReserves = EstatsReserva::all();
        return response()->json(["Status" => "Success", "Result" => $estatsReserves], 200);
    }

    //GET de una ID
    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/estatsreserva/{id}",
     *     tags={"EstatsReserva"},
     *     summary="Mostrar l'estat d'una reserva",
     *     @OA\Parameter(
     *         description="Id del estat de la reserva",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del estat de la reserva.",
     *          @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Success"),
     *          @OA\Property(property="Result",type="object")
     *           ),
     *      ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *     )
     * )
     */
    public function getEstatsReserva($id)
    {
        $estatsReserva = EstatsReserva::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $estatsReserva], 200);
    }
}
