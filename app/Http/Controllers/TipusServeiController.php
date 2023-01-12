<?php

namespace App\Http\Controllers;

use App\Models\TipusServei;

class TipusServeiController extends Controller
{
    public function getTipusServeis(){
        $tipusServeis = TipusServei::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    public function getTipusServei($id){
        $tipusServei = TipusServei::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }
}
