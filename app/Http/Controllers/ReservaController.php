<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Allotjament;
use App\Models\Reserva;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ReservaController extends Controller
{
    //GET de tot

    /**
     * @OA\Get(
     * path="/api/reserva",
     * tags={"Reserves"},
     * summary="Mostrar totes les reserves.",
     * security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *          )
     *     ),
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
    public function getReserves(Request $request)
    {
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $reserves = Reserva::all();
        return response()->json(["Status" => "Success", "Result" => $reserves], 200);
    }


    //GET de una ID

    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/reserva/{id}",
     *     tags={"Reserves"},
     *     summary="Mostrar una reserva",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Id de la reserva",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació de la reserva.",
     *          @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Success"),
     *          @OA\Property(property="data",type="object")
     *           ),
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Error"),
     *          @OA\Property(property="data",type="string", example="reserva no trobada")
     *           ),
     *     )
     * )
     */
    public function getReserva($id, Request $request)
    {
        $reserva = Reserva::findOrFail($id);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $reserva->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        return response()->json(["Status" => "Success", "Result" => $reserva], 200);
    }

    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/reserva/allotjament/{allotjamentID}",
     *     tags={"Reserves"},
     *     summary="Mostrar una reserva",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Id del allotjament",
     *         in="path",
     *         name="allotjamentID",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació de les reserves.",
     *          @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Success"),
     *          @OA\Property(property="data",type="object")
     *           ),
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Error"),
     *          @OA\Property(property="data",type="string", example="Informacio de l'error")
     *           ),
     *     )
     * )
     */
    public function getReservesAllotjament($allotjamentID, Request $request)
    {
        $reserves = Reserva::where("AllotjamentsID", "=", $allotjamentID)->get();

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        return response()->json(["Status" => "Success", "Result" => $reserves], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/reserva",
     *    tags={"Reserves"},
     *    summary="Crea una reserva",
     *    description="Crea una nova reserva.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="DataCheckIn", type="date", format="date", example="2024-10-17"),
     *           @OA\Property(property="DataCheckOut", type="date", format="date", example="2025-10-20"),
     *           @OA\Property(property="AllotjamentsID", type="number", format="number", example="3"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="success"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="error"),
     *         @OA\Property(property="data",type="string", example="Atribut DataCheckIn requerit")
     *          ),
     *       )
     *  )
     */
    public function insertReserva(Request $request)
    {
        $reserva = new Reserva();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }

        $allotjament=Allotjament::find($request->AllotjamentsID);

        if ($allotjament == null){
            return response()->json(["Status" => "Error", "Result" => "No existeix cap allotjament amb aquesta id"], 400);
        }

        $tarifa = Tarifa::where("TipusCategoriesID","=",$allotjament->TipusCategoriesID)->first();

        if ($tarifa == null){
            return response()->json(["Status" => "Error", "Result" => "Encara no existeix cap tarifa per aquest allotjament, " .
            "espera a que es crei una tarifa per la categoria del allotjament o que se li assigni una categoria a l'allotjament."], 400);
        }

        if ($tarifa->IniciTemporadaAlta <= $request->DataCheckIn && $tarifa->FiTemporadaAlta >= $request->DataCheckIn){
            $preu = $tarifa->PreuTemporadaAlta;
        }
        else {
            $preu = $tarifa->PreuTemporadaBaixa;
        }

        $results = DB::select
        (
            DB::raw("
                        SELECT
                            *
                        FROM
                            Reserves
                        WHERE
                            AllotjamentsID = :allotjamentID
                            AND (:checkIn BETWEEN DataCheckIn AND DataCheckOut
                            OR :checkOut BETWEEN DataCheckIn AND DataCheckOut)
                            AND EstatsReservesID = 1;"),
            array(
                'allotjamentID' => $request->AllotjamentsID,
                'checkIn' => $request->DataCheckIn,
                'checkOut' => $request->DataCheckOut
            )
        );

        if (count($results) > 0){
            return response()->json(['Status' => 'Error', 'Result' => 'No hi ha disponibilitat per aquest allotjament ' .
            'durant el dies solicitats'], 400);
        }

        $reserva->DataReserva = date("Y-m-d");
        $reserva->DataCheckIn = $request->DataCheckIn;
        $reserva->DataCheckOut = $request->DataCheckOut;
        $reserva->Preu = $preu;
        $reserva->EstatsReservesID = 1;
        $reserva->AllotjamentsID = $request->AllotjamentsID;
        $reserva->UsuarisID = $request->DadesUsuari->ID;

        if ($reserva->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $reserva], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error guardant'], 400);
        }
    }

    //UPDATE

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/reserva",
     *    tags={"Reserves"},
     *    summary="Modifica una reserva",
     *    description="Modifica una reserva. Format de la data: Y-m-d.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="1"),
     *           @OA\Property(property="EstatsReservesID", type="number", format="number", example="2"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="success"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *     ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="error"),
     *         @OA\Property(property="data",type="string", example="Atribut Preu requerit")
     *         ),
     *      )
     *  )
     */
    public function updateReserva(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $reserva = Reserva::find($request->ID);

        if ($reserva == null){
            return response()->json(["Status" => "Error", "Result" => "No existeix cap reserva amb aquesta id"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $reserva->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->updateValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }

        $reserva->EstatsReservesID = $request->EstatsReservesID;

        if ($reserva->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $reserva], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error actualitzant'], 400);
        }
    }

    //DELETE

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/reserva",
     *    tags={"Reserves"},
     *    summary="Esborra una reserva",
     *    description="Esborra una reserva.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="5"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="success"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="error"),
     *         @OA\Property(property="data",type="string", example="Tupla no trobada")
     *          ),
     *       )
     *      )
     *  )
     */
    public function deleteReserva(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $reserva = Reserva::find($request->ID);
        if ($reserva == null){
            return response()->json(["Status" => "Error", "Result" => "No existeix cap reserva amb aquesta ID"]);
        }

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $reserva->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $reserva->delete()) {
            return response()->json(['Status' => 'Success', 'Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error borrant'], 400);
        }
    }

    //VALIDADOR

    public function createValidator(): array
    {
        $avui = date("Y-m-d");
        return [
            "DataCheckIn" => ["required", "date", "after_or_equal:$avui"],
            "DataCheckOut" => ["required", "date", "after_or_equal:DataCheckIn"],
            "AllotjamentsID" => ["required"]
        ];
    }

    public function updateValidator(): array
    {
        $avui = date("Y-m-d");
        return [
            "ID" => ["required"],
            "EstatsReservesID" => ["required"],
        ];
    }
}
