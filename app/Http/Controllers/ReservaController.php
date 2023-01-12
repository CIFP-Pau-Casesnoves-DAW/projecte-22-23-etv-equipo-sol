<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReservaController extends Controller
{
    //GET de tot
    public function getReserves()
    {
        $reserves = Reserva::all();
        return response()->json(["Status" => "Success", "Result" => $reserves], 200);
    }


    //GET de una ID
    public function getReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $reserva], 200);
    }

    //INSERT
    public function insertReserva(Request $request)
    {
        $reserva = new Reserva();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }
        $reserva->DataReserva = date("Y-m-d");
        // $reserva->DataReserva = $request->DataReserva;
        $reserva->DataCheckIn = $request->DataCheckIn;
        $reserva->DataCheckOut = $request->DataCheckOut;
        $reserva->Preu = $request->Preu;
        $reserva->EstatsReservesID = $request->EstatsReservesID;
        $reserva->AllotjamentsID = $request->AllotjamentsID;
        $reserva->UsuarisID = $request->UsuarisID;

        if ($reserva->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $reserva], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error guardant'], 400);
        }
    }

    //VALIDADOR

    public function createValidator(): array
    {
        $avui = date("Y-m-d");
        return [
            // "DataReserva" => ["required","date_equals:$avui"],
            "DataCheckIn" => ["required", "after_or_equal:$avui"],
            "DataCheckOut" => ["required", "after_or_equal:DatacheckIn"],
            "Preu" => ["required", "min:0"],
            "EstatsReservesID" => ["required"],
            "AllotjamentsID" => ["required"],
            "UsuarisID" => ["required"]
        ];
    }
}
