<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Idioma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IdiomaController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/idioma",
     *      tags={"Idiomes"},
     *      summary="Mostrar tots els idiomes.",
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
    public function getIdiomes(){
        $idiomes = Idioma::all();
        return response()->json(["Status" => "Success","Result" => $idiomes], 200);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/idioma/{id}",
     *     tags={"Idiomes"},
     *     summary="Mostrar l'idioma al qual correspon la id passada per a la url",
     *     @OA\Parameter(
     *         description="Id del idioma",
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
    public function getIdioma($id){
        $idioma = Idioma::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $idioma], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/idioma",
     *    tags={"Idiomes"},
     *    summary="Crea un nou idioma",
     *    description="Crea un nou idioma.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="4"),
     *           @OA\Property(property="CodiISO", type="string", format="string", example="FR")
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
    public function insertIdioma(Request $request){
        $idioma = new Idioma();

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $idiomaRepetit=Idioma::find($request->ID);

        if ($idiomaRepetit != null){
            return response()->json(["Status" => "Error","Result"=> "Ja existeix un idioma amb aquesta id"], 400);
        }

        $idioma->ID = $request->ID;
        $idioma->CodiISO = $request->CodiISO;;

        if ($idioma->save()) {
            $idioma->ID = $request->ID;
            return response()->json(['Status' => 'Success','Result' => $idioma], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/idioma",
     *    tags={"Idiomes"},
     *    summary="Crea un nou idioma",
     *    description="Crea un nou idioma.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="4"),
     *           @OA\Property(property="CodiISO", type="string", format="string", example="FR")
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
    public function updateIdioma(Request $request){
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $idioma=Idioma::find($request->ID);

        if ($idioma == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap idioma amb aquesta id"], 400);
        }

        $idioma->CodiISO = $request->CodiISO;;

        if ($idioma->save()) {
            return response()->json(['Status' => 'Success','Result' => $idioma], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/idioma",
     *    tags={"Idiomes"},
     *    summary="Esborra un idioma",
     *    description="Esborra un idioma.",
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
    public function deleteIdioma(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $idioma=Idioma::find($request->ID);

        if ($idioma == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap idioma amb aquesta id"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $idioma->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "CodiISO" => ["required"],
            "ID" => ["required"]];
    }
}
