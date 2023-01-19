<?php

namespace App\Http\Controllers;

use App\Models\EstatsReserva;
use App\Http\Controllers\Controller;



class EstatsReservaController extends Controller
{
    //GET de tot
    public function getEstatsReserves()
    {
        $estatsReserves = EstatsReserva::all();
        return response()->json(["Status" => "Success", "Result" => $estatsReserves], 200);
    }

    //GET de una ID
    public function getEstatsReserva($id)
    {
        $estatsReserva = EstatsReserva::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $estatsReserva], 200);
    }
}
