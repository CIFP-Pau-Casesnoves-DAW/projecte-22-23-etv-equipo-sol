<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Traduccio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TraduccioController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/traduccio",
     *      tags={"Traduccions"},
     *      summary="Mostrar totes les traduccions.",
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
    public function getTraduccions(){
        $traduccions = Traduccio::all();
        return response()->json(["Status" => "Success","Result" => $traduccions], 200);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/traduccio/{nomIdentificatiu}",
     *     tags={"Traduccions"},
     *     summary="Mostrar les traduccions a la qual correspon el nom identificatiu pasat per la url",
     *     @OA\Parameter(
     *         description="nom identificatiu de la traduccio",
     *         in="path",
     *         name="nomIdentificatiu",
     *         required=true,
     *         @OA\Schema(type="string"),
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
    public function getTraduccio($nomIdentificatiu){
        $traduccions = Traduccio::where("NomIdentificatiu","=",$nomIdentificatiu)->get();
        return response()->json(["Status" => "Success","Result" => $traduccions], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/traduccio",
     *    tags={"Traduccions"},
     *    summary="Crea una nova traduccio per als 3 idiomes identificada per el nom",
     *    description="Crea una nova traduccio per als 3 idiomes identificada per el nom",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="TraduccioEspanyol", type="string", format="string", example="Lista de alojamientos"),
     *           @OA\Property(property="TraduccioCatala", type="string", format="string", example="Llista d'allotjaments"),
     *           @OA\Property(property="TraduccioAngles", type="string", format="string", example="Accommodations list"),
     *           @OA\Property(property="NomIdentificatiu", type="string", format="string", example="llista_allotjaments")
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
    public function insertTraduccio(Request $request){
        $traduccioEspanyol = new Traduccio();
        $traduccioCatala = new Traduccio();
        $traduccioAngles = new Traduccio();

        $validator = $this->createInsertValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $traduccioEspanyol->Traduccio = $request->TraduccioEspanyol;
        $traduccioEspanyol->NomIdentificatiu = $request->NomIdentificatiu;
        $traduccioEspanyol->IdiomesTraduccionsID = 1;

        $traduccioCatala->Traduccio = $request->TraduccioCatala;
        $traduccioCatala->NomIdentificatiu = $request->NomIdentificatiu;
        $traduccioCatala->IdiomesTraduccionsID = 2;

        $traduccioAngles->Traduccio = $request->TraduccioAngles;
        $traduccioAngles->NomIdentificatiu = $request->NomIdentificatiu;
        $traduccioAngles->IdiomesTraduccionsID = 3;

        $isInserted = DB::transaction(function() use ($traduccioEspanyol, $traduccioCatala, $traduccioAngles) {
            if ($traduccioEspanyol->save() && $traduccioCatala->save() && $traduccioAngles->save()) {
                return true;
            }
            return false;
        });

        if ($isInserted){
            return response()->json(['Status' => 'Success','Result' => [$traduccioEspanyol, $traduccioCatala, $traduccioAngles]], 200);
        }
        else{
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/traduccio/taula",
     *    tags={"Traduccions"},
     *    summary="Crea una nova traduccio per als 3 idiomes fent referenci al valor de una altra taula, nomes pot fe referenci a una taula",
     *    description="Crea una nova traduccio per als 3 idiomes fent referenci al valor de una altra taula, nomes pot fe referenci a una taula",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="TraduccioEspanyol", type="string", format="string", example="Lista de alojamientos"),
     *           @OA\Property(property="TraduccioCatala", type="string", format="string", example="Llista d'allotjaments"),
     *           @OA\Property(property="TraduccioAngles", type="string", format="string", example="Accommodations list"),
     *           @OA\Property(property="TipusServeisID", type="number", format="number", example="7"),
     *           @OA\Property(property="AllotjamentsID", type="number", format="number", example=""),
     *           @OA\Property(property="TipusVacancesID", type="number", format="number", example=""),
     *           @OA\Property(property="TipusAllotjamentsID", type="number", format="number", example=""),
     *           @OA\Property(property="IdiomesID", type="number", format="number", example="")
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
    public function insertTraduccioTaula(Request $request){
        $traduccioEspanyol = new Traduccio();
        $traduccioCatala = new Traduccio();
        $traduccioAngles = new Traduccio();

        $validator = $this->createInsertTaulaValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $IsReferencingValid = $this->checkReferencisTaules($request);

        if (!$IsReferencingValid){
            return response()->json(["Status" => "Error","Result"=>"Nomes es pot referencia a una taula, per tant nomes ".
            "hi pot haber a la request un atribut que tengui la paraula ID amb valor"], 400);
        }

        $traduccioEspanyol->Traduccio = $request->TraduccioEspanyol;
        $traduccioEspanyol->TipusServeisID = $request->TipusServeisID;
        $traduccioEspanyol->AllotjamentsID = $request->AllotjamentsID;
        $traduccioEspanyol->TipusVacancesID = $request->TipusVacancesID;
        $traduccioEspanyol->TipusAllotjamentsID = $request->TipusAllotjamentsID;
        $traduccioEspanyol->IdiomesID = $request->IdiomesID;
        $traduccioEspanyol->IdiomesTraduccionsID = 1;

        $traduccioCatala->Traduccio = $request->TraduccioCatala;
        $traduccioCatala->TipusServeisID = $request->TipusServeisID;
        $traduccioCatala->AllotjamentsID = $request->AllotjamentsID;
        $traduccioCatala->TipusVacancesID = $request->TipusVacancesID;
        $traduccioCatala->TipusAllotjamentsID = $request->TipusAllotjamentsID;
        $traduccioCatala->IdiomesID = $request->IdiomesID;
        $traduccioCatala->IdiomesTraduccionsID = 2;

        $traduccioAngles->Traduccio = $request->TraduccioAngles;
        $traduccioAngles->TipusServeisID = $request->TipusServeisID;
        $traduccioAngles->AllotjamentsID = $request->AllotjamentsID;
        $traduccioAngles->TipusVacancesID = $request->TipusVacancesID;
        $traduccioAngles->TipusAllotjamentsID = $request->TipusAllotjamentsID;
        $traduccioAngles->IdiomesID = $request->IdiomesID;
        $traduccioAngles->IdiomesTraduccionsID = 3;

        $isInserted = DB::transaction(function() use ($traduccioEspanyol, $traduccioCatala, $traduccioAngles) {
            if ($traduccioEspanyol->save() && $traduccioCatala->save() && $traduccioAngles->save()) {
                return true;
            }
            return false;
        });

        if ($isInserted){
            return response()->json(['Status' => 'Success','Result' => [$traduccioEspanyol, $traduccioCatala, $traduccioAngles]], 200);
        }
        else{
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/traduccio",
     *    tags={"Traduccions"},
     *    summary="Modifica el texte de una traduccio",
     *    description="Modifica el texte de una traduccio.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="1"),
     *           @OA\Property(property="Traduccio", type="string", format="string", example="Traduccio modificada..")
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
    public function updateTraduccio(Request $request){
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

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

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/traduccio",
     *    tags={"Traduccions"},
     *    summary="Esborra totes les traduccions amb el nom identificatiu especificat",
     *    description="Esborra totes les traduccions amb el nom identificatiu especificat",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="NomIdentificatiu", type="string", format="string", example="Nom identificatiu"),
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
    public function deleteTraduccio(Request $request){
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($request->NomIdentificatiu == null){
            return response()->json(["Status" => "Error", "Result" => "El nom identificatiu ha de tenir un valor"], 400);
        }

        $traduccions=Traduccio::where("NomIdentificatiu","=",$request->NomIdentificatiu)->get();

        if (count($traduccions) != 3){
            return response()->json(["Status" => "Error", "Result" => "El nom identificatiu es incorrecte"], 400);
        }

        $isDeleted = DB::transaction(function() use ($traduccions) {

            foreach ($traduccions as $traduccio){
                if (!$traduccio->delete()){
                    return false;
                }
            }

            return true;
        });

        if ($isDeleted) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function checkReferencisTaules(Request $request): bool{
        $referencesCount = 0;

        if ($request->TipusServeisID != null){
            $referencesCount++;
        }

        if ($request->AllotjamentsID != null){
            $referencesCount++;
        }

        if ($request->TipusVacancesID != null){
            $referencesCount++;
        }

        if ($request->TipusAllotjamentsID != null){
            $referencesCount++;
        }

        if ($request->IdiomesID != null){
            $referencesCount++;
        }

        return $referencesCount == 1;
    }

    public function createInsertValidator(): array
    {
        return [
            "TraduccioEspanyol" => ["required", "max:1000"],
            "TraduccioCatala" => ["required", "max:1000"],
            "TraduccioAngles" => ["required", "max:1000"],
            "NomIdentificatiu" => ["required", "max:100"]];
    }

    public function createInsertTaulaValidator(): array
    {
        return [
            "TraduccioEspanyol" => ["required", "max:1000"],
            "TraduccioCatala" => ["required", "max:1000"],
            "TraduccioAngles" => ["required", "max:1000"]];
    }

    public function createUpdateValidator(): array
    {
        return [
            "Traduccio" => ["required", "max:1000"]];
    }
}
