<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TarifaController extends Controller
{    /**
    * @OA\Get(
    * path="/api/tarifa",
    * tags={"Tarifes"},
    * summary="Mostrar totes les tarifes.",
    * @OA\Response(
    * response=200,
    * description="Mostrar totes les tarifes."
    * ),
    * @OA\Response(
    * response=400,
    * description="Hi ha un error."
    * ),
    * )
    */
    public function getTarifes(){
        $tarifes = Tarifa::all();
        return response()->json(["Status" => "Success","Result" => $tarifes], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tarifa/{id}",
     *     tags={"Tarifes"},
     *     summary="Mostrar una tarifa",
     *     @OA\Parameter(
     *         description="Id de la tarifa",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació de la tarifa.",
     *          @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Success"),
     *          @OA\Property(property="data",type="object")
     *           ),
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Error"),
     *          @OA\Property(property="data",type="string", example="tarifa no trobada")
     *           ),
     *     )
     * )
     */
    public function getTarifa($id){
        $tarifa = Tarifa::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tarifa], 200);
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/tarifa",
     *    tags={"Tarifes"},
     *    summary="Crea una tarifa",
     *    description="Crea una nova tarifa.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="PreuTemporadaAlta", type="number", format="number", example="1000"),
     *           @OA\Property(property="PreuTemporadaBaixa", type="number", format="number", example="800"),
     *           @OA\Property(property="IniciTemporadaAlta", type="date", format="date", example="01/06/23"),
     *           @OA\Property(property="FiTemporadaAlta", type="date", format="date", example="01/10/23"),
     *           @OA\Property(property="TipusCategoriesID", type="number", format="number", example="2"),
     *
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="success"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="error"),
     *         @OA\Property(property="data",type="string", example="Atribut categoria requerit")
     *          ),
     *       )
     *  )
     */
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

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/tarifa",
     *    tags={"Tarifes"},
     *    summary="Modifica una tarifa",
     *    description="Modifica una tarifa.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="2"),
     *           @OA\Property(property="PreuTemporadaAlta", type="number", format="number", example="1000"),
     *           @OA\Property(property="PreuTemporadaBaixa", type="number", format="number", example="800"),
     *           @OA\Property(property="IniciTemporadaAlta", type="date", format="date", example="01/06/23"),
     *           @OA\Property(property="FiTemporadaAlta", type="date", format="date", example="01/10/23"),
     *           @OA\Property(property="TipusCategoriesID", type="number", format="number", example="2"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="success"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *     ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="error"),
     *         @OA\Property(property="data",type="string", example="Atribut tarifa requerit")
     *         ),
     *      )
     *  )
     */
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

     /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/tarifa",
     *    tags={"Tarifes"},
     *    summary="Esborra una tarifa",
     *    description="Esborra una tarifa.",
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
     *         @OA\Property(property="status", type="integer", example="success"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="status", type="integer", example="error"),
     *         @OA\Property(property="data",type="string", example="Tupla no trobada")
     *          ),
     *       )
     *      )
     *  )
     */
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
