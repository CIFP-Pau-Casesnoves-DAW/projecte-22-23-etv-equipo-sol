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

    //UPDATE

    public function updateReserva(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $reserva = Reserva::findOrFail($request->ID);
        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }

        $reserva->DataCheckIn = $request->DataCheckIn;
        $reserva->DataCheckOut = $request->DataCheckOut;
        $reserva->Preu = $request->Preu;
        $reserva->EstatsReservesID = $request->EstatsReservesID;
        //DESCOMENTAR PER PODER CANVIAR A NOM DE QUI ESTA LA RESERVA
        // $reserva->UsuarisID = $request->UsuarisID; 

        if ($reserva->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $reserva], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error actualitzant'], 400);
        }
    }

    //DELETE

    public function deleteReserva(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $reserva = Reserva::findOrFail($request->ID);

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
            "EstatsReservesID" => ["required"],
            "AllotjamentsID" => ["required"],
            "UsuarisID" => ["required"]
        ];
    }
}
