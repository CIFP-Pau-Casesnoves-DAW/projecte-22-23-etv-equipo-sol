<?php

namespace App\Http\Controllers;

use App\Models\Rol;

class RolController extends Controller
{
    //GET de tot
    public function getRols()
    {
        $Rols = Rol::all();
        return response()->json(["Status" => "Success", "Result" => $Rols], 200);
    }


    //GET de una ID
    public function getRol($id)
    {
        $Rol = Rol::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $Rol], 200);
    }

}
