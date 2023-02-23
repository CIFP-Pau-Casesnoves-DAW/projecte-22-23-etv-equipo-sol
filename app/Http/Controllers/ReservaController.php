<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Reserva;
use Illuminate\Http\Request;
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
     * @OA\Response(
     * response=200,
     * description="Mostrar totes les reserves."
     * ),
     * @OA\Response(
     * response=400,
     * description="Hi ha un error."
     * ),
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
        $reserva = Reserva::find($id);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $reserva->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        return response()->json(["Status" => "Success", "Result" => $reserva], 200);
    }

    //INSERT

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
     *           @OA\Property(property="DataCheckIn", type="date", format="date", example="2023-10-17"),
     *           @OA\Property(property="DataCheckOut", type="date", format="date", example="2023-10-20"),
     *           @OA\Property(property="Preu", type="number", format="number", example="20"),
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

        $checkId = Reserva::find($request->ID);
        if ($checkId != null){
            return response()->json(["Status" => "Error", "Result" => "Id ja utilitzada"], 400);
        }

        $isValid = Validator::make($request->all(), $validator, $messages);


        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }
        $reserva->DataReserva = date("Y-m-d");
        $reserva->DataCheckIn = $request->DataCheckIn;
        $reserva->DataCheckOut = $request->DataCheckOut;
        $reserva->Preu = $request->Preu;
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
     *           @OA\Property(property="DataCheckIn", type="date", format="date", example="2023-12-23"),
     *           @OA\Property(property="DataCheckOut", type="date", format="date", example="2023-12-27"),
     *           @OA\Property(property="Preu", type="number", format="number", example="20"),
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

        $checkId = Reserva::find($request->ID);
        if ($checkId == null){
            return response()->json(["Status" => "Error", "Result" => "No existeix cap reserva amb aquesta id"], 400);
        }

        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $reserva = Reserva::find($request->ID);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $reserva->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->updateValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }

        $reserva->DataCheckIn = $request->DataCheckIn;
        $reserva->DataCheckOut = $request->DataCheckOut;
        $reserva->Preu = $request->Preu;
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
            "Preu" => ["required", "min:0"],
            "AllotjamentsID" => ["required"]
        ];
    }

    public function updateValidator(): array
    {
        $avui = date("Y-m-d");
        return [
            "DataCheckIn" => ["required", "date", "after_or_equal:$avui"],
            "DataCheckOut" => ["required", "date", "after_or_equal:DataCheckIn"],
            "Preu" => ["required", "min:0"]
        ];
    }
}
