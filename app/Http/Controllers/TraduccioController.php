<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Traduccio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TraduccioController extends Controller
{
    public function getTraduccions(){
        $traduccions = Traduccio::all();
        return response()->json(["Status" => "Success","Result" => $traduccions], 200);
    }

    public function getTraduccio($id){
        $traduccio = Traduccio::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $traduccio], 200);
    }

    //Per ara aixi, teoricament haura de rebre les 3 traduccions per crearles amb un mateix nou identificatiu
    public function insertTraduccio(Request $request){
        $traduccio = new Traduccio();

        $validator = $this->createInsertValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $traduccio->Traduccio = $request->Traduccio;
        $traduccio->NomIdentificatiu = $request->NomIdentificatiu;
        $traduccio->IdiomesTraduccionsID = $request->IdiomesTraduccionsID;

        if ($traduccio->save()) {
            return response()->json(['Status' => 'Success','Result' => $traduccio], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    //hauria de ser de un altre manera, identificant via nomIdentificatiu + idioma, mira com
    public function updateTraduccio(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $traduccio=Traduccio::findOrFail($request->ID);
        $validator = $this->createUpdateValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $traduccio->Traduccio = $request->Traduccio;

        if ($traduccio->save()) {
            return response()->json(['Status' => 'Success','Result' => $traduccio], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    public function deleteTraduccio(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $traduccio=Traduccio::findOrFail($request->ID);

        if ($isDeleted = $traduccio->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createInsertValidator(): array
    {
        return [
            "Traduccio" => ["required", "max:1000"],
            "NomIdentificatiu" => ["required", "max:100"],
            "IdiomesTraduccionsID" => ["required"]];
    }

    public function createUpdateValidator(): array
    {
        return [
            "Traduccio" => ["required", "max:1000"]];
    }
}
