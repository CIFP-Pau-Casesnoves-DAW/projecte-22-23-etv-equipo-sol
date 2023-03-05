<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\TipusCategoria;
use App\Models\TipusServei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipusCategoriaController extends Controller
{

    /**
    * @OA\Get(
    * path="/api/tipuscategoria",
    * tags={"Tipus Categoria"},
    * summary="Mostrar tots els tipus de categoria.",
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
    public function getTipusCategories(){
        $tipusServeis = TipusCategoria::all();
        return response()->json(["Status" => "Success","Result" => $tipusServeis], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipuscategoria/{id}",
     *     tags={"Tipus Categoria"},
     *     summary="Mostrar un tipus de categoria",
     *     @OA\Parameter(
     *         description="Id del tipus de categoria",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del tipus de categoria.",
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
     *          @OA\Property(property="data",type="string", example="tipus de categoria no trobada")
     *           ),
     *     )
     * )
     */
    public function getTipusCategoria($id){
        $tipusServei = TipusCategoria::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusServei], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/tipuscategoria",
     *    tags={"Tipus Categoria"},
     *    summary="Crea un tipus de categoria",
     *    description="Crea un tipus de categoria.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="4"),
     *           @OA\Property(property="Nom", type="string", format="string", example="Basura"),
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
    public function insertTipusCategoria(Request $request){

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusCategoria = new TipusCategoria();
        $tipusCategoriaExistent=TipusCategoria::find($request->ID);
        if ($tipusCategoriaExistent != null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipus de categoria amb aquesta id"], 400);
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

        $tipusCategoria->ID = $request->ID;
        $tipusCategoria->Nom = $request->Nom;

        if ($tipusCategoria->save()) {
            $tipusCategoria->ID = $request->ID;
            return response()->json(['Status' => 'Success','Result' => $tipusCategoria], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/tipuscategoria",
     *    tags={"Tipus Categoria"},
     *    summary="Modifica un tipus de categoria",
     *    description="Modifica un tipus de categoria.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="4"),
     *           @OA\Property(property="Nom", type="string", format="string", example="Basura"),
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
     *         @OA\Property(property="data",type="string", example="Atribut categoria requerit")
     *         ),
     *      )
     *  )
     */
    public function updateTipusCategoria(Request $request){
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusCategoria=TipusCategoria::find($request->ID);

        if ($tipusCategoria == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipus de categoria amb aquesta id"], 400);
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
        $tipusCategoria->ID = $request->ID;
        $tipusCategoria->Nom = $request->Nom;

        if ($tipusCategoria->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusCategoria], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/tipuscategoria",
     *    tags={"Tipus Categoria"},
     *    summary="Esborra un tipus de categoria",
     *    description="Esborra un tipus de categoria.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="4"),
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
    public function deleteTipusCategoria(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tipusCategoria=TipusCategoria::find($request->ID);

        if ($tipusCategoria == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipus de categoria amb aquesta id"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $tipusCategoria->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "ID" => ["required"],
            "Nom" => ["required"]];
    }
}
