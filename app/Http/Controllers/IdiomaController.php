<?php

namespace App\Http\Controllers;

use App\Models\Idioma;

class IdiomaController extends Controller
{
    public function getIdiomes(){
        $idiomes = Idioma::all();
        return response()->json(["Status" => "Success","Result" => $idiomes], 200);
    }

    public function getIdioma($id){
        $idioma = Idioma::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $idioma], 200);
    }
}
