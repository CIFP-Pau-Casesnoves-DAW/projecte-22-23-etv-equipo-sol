<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Allotjament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AllotjamentController extends Controller
{
    public function getAllotjaments(){
        $allotjmanets = Allotjament::all();
        return response()->json(["status" => "Success","result" => $allotjmanets], 200);
    }

    public function getAllotjament($id){
        $allotjmanet = Allotjament::findOrFail($id);
        return response()->json(["status" => "Success","result" => $allotjmanet], 200);
    }

    public function insertAllotjament(Request $request){
        $allotjament = new Allotjament();

        $validator = $this->createValidators();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["status" => "Error","result"=>$isValid->errors()], 400);
        }

        $allotjament->NumeroRegistre = $request->NumeroRegistre;
        $allotjament->NomComercial = $request->NomComercial;
        $allotjament->Direccio = $request->Direccio;
        $allotjament->Destacat = $request->Destacat;
        $allotjament->NumeroLlits = $request->NumeroLlits;
        $allotjament->NumeroPersones = $request->NumeroPersones;
        $allotjament->NumeroHabitacions = $request->NumeroHabitacions;
        $allotjament->NumeroBanys = $request->NumeroBanys;
        $allotjament->UsuarisID = $request->UsuarisID;
        $allotjament->MunicipisID = $request->MunicipisID;
        $allotjament->TipusVacancesID = $request->TipusVacancesID;
        $allotjament->TipusAllotjamentsID = $request->TipusAllotjamentsID;

        if ($allotjament->save()) {
            return response()->json(['status' => 'Success','result' => $allotjament], 200);
        } else {
            return response()->json(['status' => 'Error','result' => 'Error guardant'], 400);
        }
    }

    public function updateAllotjament(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["status" => "Error","result"=>"Incorrect ID"], 400);
        }

        $allotjament=Allotjament::findOrFail($request->ID);
        $validator = $this->createValidators();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["status" => "Error","result"=>$isValid->errors()], 400);
        }

        $allotjament->NumeroRegistre = $request->NumeroRegistre;
        $allotjament->NomComercial = $request->NomComercial;
        $allotjament->Direccio = $request->Direccio;
        $allotjament->Destacat = $request->Destacat;
        $allotjament->Valoracio = $request->Valoracio;
        $allotjament->NumeroLlits = $request->NumeroLlits;
        $allotjament->NumeroPersones = $request->NumeroPersones;
        $allotjament->NumeroHabitacions = $request->NumeroHabitacions;
        $allotjament->NumeroBanys = $request->NumeroBanys;
        $allotjament->UsuarisID = $request->UsuarisID;
        $allotjament->MunicipisID = $request->MunicipisID;
        $allotjament->TipusVacancesID = $request->TipusVacancesID;
        $allotjament->TipusAllotjamentsID = $request->TipusAllotjamentsID;
        $allotjament->TipusCategoriesID = $request->TipusCategoriesID;

        if ($allotjament->save()) {
            return response()->json(['status' => 'Success','result' => $allotjament], 200);
        } else {
            return response()->json(['status' => 'Error','result' => 'Error actualitzant'], 400);
        }
    }

    public function deleteAllotjament(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["status" => "Error","result"=>"Incorrect ID"], 400);
        }

        $allotjament=Allotjament::findOrFail($request->ID);

        if ($isDeleted = $allotjament->delete()) {
            return response()->json(['status' => 'Success','result' => $isDeleted], 200);
        } else {
            return response()->json(['status' => 'Error','result' => 'Error borrant'], 400);
        }
    }

    public function createValidators(): array
    {
        return [
            "NumeroRegistre" => ["required", "max:14","min:14"],
            "NomComercial" => ["required", "max:200"],
            "Direccio" => ["required", "max:200"],
            "Destacat" => ["required"],
            "NumeroLlits" => ["required"],
            "NumeroPersones" => ["required"],
            "NumeroHabitacions" => ["required"],
            "NumeroBanys" => ["required"],
            "UsuarisID" => ["required"],
            "MunicipisID" => ["required"],
            "TipusVacancesID" => ["required"],
            "TipusAllotjamentsID" => ["required"]];
    }
}
