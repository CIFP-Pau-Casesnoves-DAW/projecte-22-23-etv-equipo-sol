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
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="Status", type="string", example="Success"),
     *              @OA\Property(property="Result",type="object")
     *          )
     *      )
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
     *     path="/api/traduccio/{id}",
     *     tags={"Traduccions"},
     *     summary="Mostrar la traduccio a la qual correspon la id passada per la url",
     *     @OA\Parameter(
     *         description="Id de la traduccio",
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
     * )
     */
    public function getTraduccio($id){
        $traduccio = Traduccio::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $traduccio], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/traduccio",
     *    tags={"Traduccions"},
     *    summary="Crea una nova traduccio per als 3 idiomes",
     *    description="Crea una nova traduccio per als 3 idiomes",
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

    //hauria de ser de un altre manera, identificant via nomIdentificatiu + idioma, mira com
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

    //hauria de borrar les 3 traduccions
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/traduccio",
     *    tags={"Traduccions"},
     *    summary="Esborra una traduccio",
     *    description="Esborra una traduccio.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="3"),
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
            "TraduccioEspanyol" => ["required", "max:1000"],
            "TraduccioCatala" => ["required", "max:1000"],
            "TraduccioAngles" => ["required", "max:1000"],
            "NomIdentificatiu" => ["required", "max:100"]];
    }

    public function createUpdateValidator(): array
    {
        return [
            "Traduccio" => ["required", "max:1000"]];
    }
}
