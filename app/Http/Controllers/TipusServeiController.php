<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\TipusServei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipusServeiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/tipusservei",
     *      tags={"Tipus Servei"},
     *      summary="Mostrar tots els tipus de serveis.",
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
    public function getTipusServeis(){
        $tipusServeis = TipusServei::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipusservei/{id}",
     *     tags={"Tipus Servei"},
     *     summary="Mostrar el tipus de servei al qual correspon la id passada per a la url",
     *     @OA\Parameter(
     *         description="Id del comentari",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
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
    public function getTipusServei($id){
        $tipusServei = TipusServei::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/tipusservei",
     *    tags={"Tipus Servei"},
     *    summary="Crea un nou tipusServei",
     *    description="Crea un nou tipusServei.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="45"),
     *           @OA\Property(property="NomIdentificatiu", type="string", format="string", example="Hamacas")
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
    public function insertTipusServei(Request $request){
        $tipusServei = new TipusServei();

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $tipusServeiRepetit=TipusServei::find($request->ID);

        if ($tipusServeiRepetit != null){
            return response()->json(["Status" => "Error","Result"=> "Ja existeix un tipusServei amb aquesta id"], 400);
        }

        $tipusServei->ID = $request->ID;
        $tipusServei->NomIdentificatiu = $request->NomIdentificatiu;;

        if ($tipusServei->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusServei], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/tipusservei",
     *    tags={"Tipus Servei"},
     *    summary="Crea un nou tipusServei",
     *    description="Crea un nou tipusServei.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="45"),
     *           @OA\Property(property="NomIdentificatiu", type="string", format="string", example="Hamacas")
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
    public function updateTipusServei(Request $request){
        $tipusServei = new TipusServei();

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $tipusServeiExistent=TipusServei::find($request->ID);

        if ($tipusServeiExistent == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipusServei amb aquesta id"], 400);
        }

        $tipusServei->NomIdentificatiu = $request->NomIdentificatiu;;

        if ($tipusServei->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusServei], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/tipusservei",
     *    tags={"TipusServeis"},
     *    summary="Esborra un tipusServei",
     *    description="Esborra un tipusServei.",
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
    public function deleteTipusServei(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tipusServei=TipusServei::find($request->ID);

        if ($tipusServei == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipusServei amb aquesta id"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $tipusServei->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "NomIdentificatiu" => ["required"],
            "ID" => ["required"]];
    }
}
