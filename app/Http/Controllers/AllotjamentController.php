<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Allotjament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AllotjamentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/allotjament",
     *      tags={"Allotjaments"},
     *      summary="Mostrar tots els allotjaments.",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *          )
     *      ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *     )
     * )
     */
    public function getAllotjaments(){
        $allotjmanets = Allotjament::all();
        return response()->json(["Status" => "Success","Result" => $allotjmanets], 200);
    }


    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/allotjament/{id}",
     *     tags={"Allotjaments"},
     *     summary="Mostrar l'allotjament al qual correspon la id passada per la url",
     *     @OA\Parameter(
     *         description="Id de l'allotjament",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *          )
     *     ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *     )
     * )
     */
    public function getAllotjament($id){
        $allotjmanet = Allotjament::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $allotjmanet], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/allotjament",
     *    tags={"Allotjaments"},
     *    summary="Crea un nou allotjament",
     *    description="Crea un nou allotjament.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="NumeroRegistre", type="string", format="string", example="A1112223334444"),
     *           @OA\Property(property="NomComercial", type="string", format="string", example="Can Tomeu Llarg"),
     *           @OA\Property(property="Direccio", type="string", format="string", example="C\ Gran 35"),
     *           @OA\Property(property="NumeroLlits", type="number", format="number", example="3"),
     *           @OA\Property(property="NumeroPersones", type="number", format="number", example="3"),
     *           @OA\Property(property="NumeroHabitacions", type="number", format="number", example="7"),
     *           @OA\Property(property="NumeroBanys", type="number", format="number", example="2"),
     *           @OA\Property(property="MunicipisID", type="number", format="number", example="7"),
     *           @OA\Property(property="TipusVacancesID", type="number", format="number", example="9"),
     *           @OA\Property(property="TipusAllotjamentsID", type="number", format="number", example="2")
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *         ),
     *    ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *     )
     *  )
     */
    public function insertAllotjament(Request $request){

        $allotjament = new Allotjament();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $allotjament->NumeroRegistre = $request->NumeroRegistre;
        $allotjament->NomComercial = $request->NomComercial;
        $allotjament->Direccio = $request->Direccio;
        $allotjament->Destacat = 0;
        $allotjament->NumeroLlits = $request->NumeroLlits;
        $allotjament->NumeroPersones = $request->NumeroPersones;
        $allotjament->NumeroHabitacions = $request->NumeroHabitacions;
        $allotjament->NumeroBanys = $request->NumeroBanys;
        $allotjament->UsuarisID = $request->DadesUsuari->ID;
        $allotjament->MunicipisID = $request->MunicipisID;
        $allotjament->TipusVacancesID = $request->TipusVacancesID;
        $allotjament->TipusAllotjamentsID = $request->TipusAllotjamentsID;

        if ($allotjament->save()) {
            return response()->json(['Status' => 'Success','Result' => $allotjament], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/allotjament",
     *    tags={"Allotjaments"},
     *    summary="Modifica un allotjament",
     *    description="Modifica un allotjament.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="1"),
     *           @OA\Property(property="NumeroRegistre", type="string", format="string", example="A1112223334444"),
     *           @OA\Property(property="NomComercial", type="string", format="string", example="Can Tomeu Llarg"),
     *           @OA\Property(property="Direccio", type="string", format="string", example="C\ Gran 35"),
     *           @OA\Property(property="NumeroLlits", type="number", format="number", example="3"),
     *           @OA\Property(property="NumeroPersones", type="number", format="number", example="3"),
     *           @OA\Property(property="NumeroHabitacions", type="number", format="number", example="7"),
     *           @OA\Property(property="NumeroBanys", type="number", format="number", example="2"),
     *           @OA\Property(property="MunicipisID", type="number", format="number", example="7"),
     *           @OA\Property(property="TipusVacancesID", type="number", format="number", example="9"),
     *           @OA\Property(property="TipusAllotjamentsID", type="number", format="number", example="2")
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *         ),
     *    ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *    )
     * )
     */
    public function updateAllotjament(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $allotjament=Allotjament::findOrFail($request->ID);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $allotjament->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $allotjament->NumeroRegistre = $request->NumeroRegistre;
        $allotjament->NomComercial = $request->NomComercial;
        $allotjament->Direccio = $request->Direccio;
        $allotjament->Valoracio = $request->Valoracio;
        $allotjament->NumeroLlits = $request->NumeroLlits;
        $allotjament->NumeroPersones = $request->NumeroPersones;
        $allotjament->NumeroHabitacions = $request->NumeroHabitacions;
        $allotjament->NumeroBanys = $request->NumeroBanys;
        $allotjament->MunicipisID = $request->MunicipisID;
        $allotjament->TipusVacancesID = $request->TipusVacancesID;
        $allotjament->TipusAllotjamentsID = $request->TipusAllotjamentsID;
        $allotjament->TipusCategoriesID = $request->TipusCategoriesID;

        if ($allotjament->save()) {
            return response()->json(['Status' => 'Success','Result' => $allotjament], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/allotjament",
     *    tags={"Allotjaments"},
     *    summary="Esborra un allotjament",
     *    description="Esborra un allotjament.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="5"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="bool", example="true")
     *         ),
     *    ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Error"),
     *              @OA\Property(property="Result",type="string", example="Informacio de l'error")
     *         ),
     *    )
     *  )
     */
    public function deleteAllotjament(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $allotjament=Allotjament::findOrFail($request->ID);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $allotjament->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }


        if ($isDeleted = $allotjament->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "NumeroRegistre" => ["required", "max:14","min:14"],
            "NomComercial" => ["required", "max:200"],
            "Direccio" => ["required", "max:200"],
            "NumeroLlits" => ["required"],
            "NumeroPersones" => ["required"],
            "NumeroHabitacions" => ["required"],
            "NumeroBanys" => ["required"],
            "MunicipisID" => ["required"],
            "TipusVacancesID" => ["required"],
            "TipusAllotjamentsID" => ["required"]];
    }
}
