<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Comentari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComentariController extends Controller
{
    public function getComentaris(){
        $comentaris = Comentari::all();
        return response()->json(["Status" => "Success","Result" => $comentaris], 200);
    }

    public function getComentari($id){
        $comentari = Comentari::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $comentari], 200);
    }

    public function insertComentari(Request $request){
        $comentari = new Comentari();

        $validator = $this->createInsertValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $comentari->Comentari = $request->Comentari;
        $comentari->Valoracio = $request->Valoracio;
        $comentari->DataCreacio = $request->DataCreacio;
        $comentari->AllotjamentsID = $request->AllotjamentsID;
        $comentari->UsuarisID = $request->UsuarisID;

        if ($comentari->save()) {
            return response()->json(['Status' => 'Success','Result' => $comentari], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    public function updateComentari(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $comentari=Comentari::findOrFail($request->ID);
        $validator = $this->createUpdateValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $comentari->Comentari = $request->Comentari;
        $comentari->Valoracio = $request->Valoracio;

        if ($comentari->save()) {
            return response()->json(['Status' => 'Success','Result' => $comentari], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    public function deleteComentari(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $comentari=Comentari::findOrFail($request->ID);

        if ($isDeleted = $comentari->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createInsertValidator(): array
    {
        return [
            "Comentari" => ["required", "max:500"],
            "Valoracio" => ["required"],
            "DataCreacio" => ["required"],
            "AllotjamentsID" => ["required"],
            "UsuarisID" => ["required"]];
    }

    public function createUpdateValidator(): array
    {
        return [
            "Comentari" => ["required", "max:500"],
            "Valoracio" => ["required"]];
    }
}
