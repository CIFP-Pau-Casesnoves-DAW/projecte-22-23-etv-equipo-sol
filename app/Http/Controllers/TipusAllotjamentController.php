<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\TipusAllotjament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipusAllotjamentController extends Controller
{
        /**
    * @OA\Get(
    * path="/api/tipusallotjament",
    * tags={"Tipus Allotjament"},
    * summary="Mostrar tots els tipus de allotjaments.",
    * @OA\Response(
    * response=200,
    * description="Mostrar tots els tipus de allotjaments."
    * ),
    * @OA\Response(
    * response=400,
    * description="Hi ha un error."
    * ),
    * )
    */
    public function getTipusAllotjaments(){
        $tipusServeis = TipusAllotjament::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipusallotjament/{id}",
     *     tags={"Tipus Allotjament"},
     *     summary="Mostrar un tipus d'allotjament",
     *     @OA\Parameter(
     *         description="Id del tipus d'allotjament",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del tipus d'allotjament.",
     *          @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="200"),
     *          @OA\Property(property="data",type="object")
     *           ),    
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="Error"),
     *          @OA\Property(property="data",type="string", example="tipus d'allotjament no trobada")
     *           ),
     *     )
     * )
     */
    public function getTipusAllotjament($id){
        $tipusServei = TipusAllotjament::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }

 /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/tipusallotjament",
     *    tags={"Tipus Allotjament"},
     *    summary="Crea un tipus de allotjament",
     *    description="Crea un tipus de allotjament.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="8"),
     *           @OA\Property(property="NomIdentificatiu", type="string", format="string", example="Exemple"),
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
     *         @OA\Property(property="data",type="string", example="Atribut requerit")
     *          ),
     *       )
     *  )
     */
    public function insertTipusAllotjament(Request $request){

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusAllotjaments = new TipusAllotjament();
        $tipusAllotjamentsExistent=TipusAllotjament::find($request->ID);
        if ($tipusAllotjamentsExistent != null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipus de allotjament amb aquesta id"], 400);
        }


        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $tipusAllotjaments->ID = $request->ID;
        $tipusAllotjaments->NomIdentificatiu = $request->NomIdentificatiu;

        if ($tipusAllotjaments->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusAllotjaments], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/tipusallotjament",
     *    tags={"Tipus Allotjament"},
     *    summary="Modifica un tipus de allotjament",
     *    description="Modifica un tipus de allotjament.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="8"),
     *           @OA\Property(property="NomIdentificatiu", type="string", format="string", example="ExempleModificat"),
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
     *         @OA\Property(property="data",type="string", example="Atribut requerit")
     *         ),
     *      )
     *  )
     */
    public function updateTipusAllotjament(Request $request){
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusAllotjaments=TipusAllotjament::find($request->ID);

        if ($tipusAllotjaments == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipus de allotjament amb aquesta id"], 400);
        }

        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }
        $tipusAllotjaments->ID = $request->ID;
        $tipusAllotjaments->NomIdentificatiu = $request->NomIdentificatiu;

        if ($tipusAllotjaments->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusAllotjaments], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/tipusallotjament",
     *    tags={"Tipus Allotjament"},
     *    summary="Esborra un tipus d'allotjament",
     *    description="Esborra un tipus d'allotjament.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="8"),
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
    public function deleteTipusAllotjament(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tipusAllotjaments=TipusAllotjament::find($request->ID);

        if ($tipusAllotjaments == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipus d'allotjament amb aquesta id"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $tipusAllotjaments->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "ID" => ["required"],
            "NomIdentificatiu" => ["required"]];
    }

}
