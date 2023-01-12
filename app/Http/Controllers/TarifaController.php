<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TarifaController extends Controller
{
    public function getTarifes(){
        $tarifes = Tarifa::all();
        return response()->json(["Status" => "Success","Result" => $tarifes], 200);
    }

    public function getTarifa($id){
        $tarifa = Tarifa::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tarifa], 200);
    }

    public function insertTarifa(Request $request){
        $tarifa = new Tarifa();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $tarifa->PreuTemporadaBaixa = $request->PreuTemporadaBaixa;
        $tarifa->PreuTemporadaAlta = $request->PreuTemporadaAlta;
        $tarifa->IniciTemporadaAlta = $request->IniciTemporadaAlta;
        $tarifa->FiTemporadaAlta = $request->FiTemporadaAlta;
        $tarifa->TipusCategoriesID = $request->TipusCategoriesID;

        if ($tarifa->save()) {
            return response()->json(['Status' => 'Success','Result' => $tarifa], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    public function updateTarifa(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tarifa=Tarifa::findOrFail($request->ID);
        $validator = $this->createValidatorUpdate();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }
        $tarifa->PreuTemporadaBaixa = $request->PreuTemporadaBaixa;
        $tarifa->PreuTemporadaAlta = $request->PreuTemporadaAlta;
        $tarifa->IniciTemporadaAlta = $request->IniciTemporadaAlta;
        $tarifa->FiTemporadaAlta = $request->FiTemporadaAlta;

        if ($tarifa->save()) {
            return response()->json(['Status' => 'Success','Result' => $tarifa], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    public function deleteTarifa(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tarifa=Tarifa::findOrFail($request->ID);

        if ($isDeleted = $tarifa->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "PreuTemporadaBaixa" => ["required"],
            "PreuTemporadaAlta" => ["required"],
            "IniciTemporadaAlta" => ["required"],
            "FiTemporadaAlta" => ["required"],
            "TipusCategoriesID" => ["required"]];
    }

    public function createValidatorUpdate(): array
    {
        return [
            "PreuTemporadaBaixa" => ["required"],
            "PreuTemporadaAlta" => ["required"],
            "IniciTemporadaAlta" => ["required"],
            "FiTemporadaAlta" => ["required"]];
    }
}
