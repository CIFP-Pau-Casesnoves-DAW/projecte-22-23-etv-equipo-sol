<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuariController extends Controller
{
    //GET de tot
    public function getUsuaris()
    {
        $usuaris = Usuari::all();
        return response()->json(["Status" => "Success", "Result" => $usuaris], 200);
    }


    //GET de una ID
    public function getUsuari($id)
    {
        $usuari = Usuari::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $usuari], 200);
    }

    //INSERT
    public function insertUsuari(Request $request)
    {
        $usuari = new Usuari();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }
        $usuari->Nom = $request->Nom;
        $usuari->Llinatges = $request->Llinatges;
        $usuari->Contrasenya = $request->Contrasenya;
        $usuari->CorreuElectronic = $request->CorreuElectronic;
        $usuari->DNI = $request->DNI;
        $usuari->Telefon = $request->Telefon;
        $usuari->RolsID = $request->RolsID;
        $usuari->Token = $request->Token;
        $usuari->ExpiracioToken = $request->ExpiracioToken;


        if ($usuari->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $usuari], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error guardant'], 400);
        }
    }

    //UPDATE
    public function updateUsuari(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $usuari = Usuari::findOrFail($request->ID);
        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }

        //$usuari->Nom = $request->Nom;
        //$usuari->Llinatges = $request->Llinatges;
        $usuari->Contrasenya = $request->Contrasenya;
        $usuari->CorreuElectronic = $request->CorreuElectronic;
        $usuari->DNI = $request->DNI;
        $usuari->Telefon = $request->Telefon;
        $usuari->RolsID = $request->RolsID;
        $usuari->Token = $request->Token;
        //$usuari->ExpiracioToken = $request->ExpiracioToken;
 
        if ($usuari->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $usuari], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error actualitzant'], 400);
        }
    }

    //DELETE

    public function deleteUsuari(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        $usuari = Usuari::findOrFail($request->ID);

        if ($isDeleted = $usuari->delete()) {
            return response()->json(['Status' => 'Success', 'Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error borrant'], 400);
        }
    }

    //VALIDADOR

    public function createValidator(): array
    {
        return [
            "Nom" => ["required"],
            "Llinatges" => ["required"],
            "Contrasenya" => ["required"],
            "CorreuElectronic" => ["required"],
            "DNI" => ["required"],
            "Telefon" => ["required"],
            "RolsID" => ["required"],
            // "Token" => ["required"],
            // "ExpiracioToken" => ["required"],
        ];
    }
}
