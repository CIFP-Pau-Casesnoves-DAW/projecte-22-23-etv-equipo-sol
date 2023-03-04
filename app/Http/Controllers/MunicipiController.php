<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Municipi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MunicipiController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/municipi",
     * tags={"Municipis"},
     * summary="Mostrar tots els municipis.",
     * @OA\Response(
     * response=200,
     * description="Mostrar tots els municipis.",
     * @OA\JsonContent(
     * @OA\Property(property="Status", type="string", example="Success"),
     * @OA\Property(property="Result",type="object")
     * ),
     * ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Error"),
     *          @OA\Property(property="Result",type="string", example="Municipis no trobats")
     *           ),
     *     )
     * )
     */
    public function getMunicipis(){
        $municipis = Municipi::all();
        return response()->json(["Status" => "Success","Result" => $municipis], 200);
    }

    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/municipi/{id}",
     *     tags={"Municipis"},
     *     summary="Mostrar un municipi",
     *     @OA\Parameter(
     *         description="Id del municipi",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del municipi.",
     *          @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Success"),
     *          @OA\Property(property="Result",type="object")
     *           ),
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Error"),
     *          @OA\Property(property="Result",type="string", example="Tipus de municipi no trobat")
     *           ),
     *     )
     * )
     */
    public function getMunicipi($id){
        $municipi = Municipi::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $municipi], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/municipi",
     *    tags={"Municipis"},
     *    summary="Crea un municicpi",
     *    description="Crea un municicpi.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="2"),
     *           @OA\Property(property="Nom", type="varchar", example="Palma"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="Status", type="integer", example="Success"),
     *         @OA\Property(property="Result",type="object")
     *          ),
     *       ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="Status", type="string", example="Error"),
     *         @OA\Property(property="Result",type="string", example="No sa pogut insertar el municipi")
     *          ),
     *       )
     *  )
     */

    public function insertMunicipi(Request $request){
        $municipi = new Municipi();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $municipiRepetit=Municipi::find($request->ID);

        if ($municipiRepetit != null){
            return response()->json(["Status" => "Error","Result"=> "Ja existeix un municipi amb aquesta id"], 400);
        }

        $municipi->ID = $request->ID;
        $municipi->Nom = $request->Nom;

        if ($municipi->save()) {
            $municipi->ID = $request->ID;
            return response()->json(['Status' => 'Success','Result' => $municipi], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/municipi",
     *    tags={"Municipis"},
     *    summary="Modifica un municipi",
     *    description="Modifica un municipi.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="2"),
     *           @OA\Property(property="Nom", type="varchar", example="Palma"),
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="Status", type="integer", example="Success"),
     *         @OA\Property(property="Result",type="object")
     *          ),
     *     ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="Status", type="string", example="Error"),
     *         @OA\Property(property="Result",type="string", example="Atribut Nom requerid")
     *         ),
     *      )
     *  )
     */
    public function updateMunicipi(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $validator = $this->createValidatorUpdate();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $municipi=Municipi::find($request->ID);

        if ($municipi == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap municipi amb aquesta id"], 400);
        }

        $municipi->Nom = $request->Nom;

        if ($municipi->save()) {
            $municipi->ID = $request->ID;
            return response()->json(['Status' => 'Success','Result' => $municipi], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/municipi",
     *    tags={"Municipis"},
     *    summary="Esborra un municipi",
     *    description="Esborra un municipi.",
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
     *         @OA\Property(property="Status", type="integer", example="Success"),
     *         @OA\Property(property="Result",type="object")
     *          ),
     *       ),
     *    @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *         @OA\Property(property="Status", type="integer", example="Error"),
     *         @OA\Property(property="Result",type="string", example="Tupla no trobada")
     *          ),
     *       )
     *      )
     *  )
     */
    public function deleteMunicipi(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $municipi=Municipi::find($request->ID);

        if ($municipi == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap municipi amb aquesta id"], 400);
        }

        if ($isDeleted = $municipi->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "ID" => ["required"],
            "Nom" => ["required"]
        ];
    }

    public function createValidatorUpdate(): array
    {
        return [
            "Nom" => ["required"]
        ];
    }
}
