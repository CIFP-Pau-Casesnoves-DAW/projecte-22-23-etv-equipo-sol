<?php

namespace App\Http\Controllers;

use App\Models\TipusVacances;
use Illuminate\Http\Request;

class TipusVacancesController extends Controller
{
    /**
    * @OA\Get(
        * path="/api/tipusvacances",
        * tags={"Tipus Vacances"},
        * summary="Mostrar tots els tipus de vacances.",
        * @OA\Response(
            * response=200,
            * description="Mostrar tots els tipus de vacances.",
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
    *          @OA\Property(property="Result",type="string", example="Tipus de vacances no trobades")
    *           ),
    *     )
    * )
    */
    public function getTipusVacances(){
        $tipusVacances = TipusVacances::all();
        return response()->json(["Status" => "Success","Result" => $tipusVacances], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/tipusvacances/{id}",
     *     tags={"Tipus Vacances"},
     *     summary="Mostrar un tipus de vacances",
     *     @OA\Parameter(
     *         description="Id del tipus de vacances",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del tipus de vacances.",
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
     *          @OA\Property(property="Result",type="string", example="Tipus de vacances no trobada")
     *           ),
     *     )
     * )
     */
    public function getTipusVacance($id){
        $tipusVacances = TipusVacances::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $tipusVacances], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/tipusvacances",
     *    tags={"Tipus Vacances"},
     *    summary="Crea un tipus de vacances",
     *    description="Crea un nou tipus de vacances.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="ID", type="number", format="number", example="2"),
     *              @OA\Property(property="NomIdentificatiu", type="string", example="Muntanya"),
     *           )
     *        )
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
     *         @OA\Property(property="Result",type="string", example="No sa pogut insertar el nou tipus vacances")
     *          ),
     *       )
     *  )
     */

    public function insertTipusVacance(Request $request){
        $tipusvacances = new TipusVacances();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusvacancesRepetir=TipusVacances::find($request->ID);

        if ($tipusvacancesRepetir != null){
            return response()->json(["Status" => "Error","Result"=> "Ja existeix un tipus de vacances amb aquesta id"], 400);
        }

        $tipusvacances->ID = $request->ID;
        $tipusvacances->NomIdentificatiu = $request->NomIdentificatiu;

        if ($tipusvacances->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusvacances], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/tipusvacances",
     *    tags={"Tipus Vacances"},
     *    summary="Modifica un tipus de vacances",
     *    description="Modifica un tipus de vacances.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="2"),
     *           @OA\Property(property="NomIdentificatiu", type="varchar", example="Muntanya"),
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
     *         @OA\Property(property="Result",type="string", example="Atribut NomIdentificatiu requerid")
     *         ),
     *      )
     *  )
     */
    public function updateTipusVacance(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tipusvacances=TipusVacances::findOrFail($request->ID);
        $validator = $this->createValidatorUpdate();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusvacances->NomIdentificatiu = $request->NomIdentificatiu;

        if ($tipusvacances->save()) {
            return response()->json(['Status' => 'Success','Result' => $tipusvacances], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/tipusvacances",
     *    tags={"Tipus Vacances"},
     *    summary="Esborra un tipues de vacances",
     *    description="Esborra un tipues de vacances.",
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
    public function deleteTipusVacance(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $tipusvacances=TipusVacances::findOrFail($request->ID);

        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $tipusvacances->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "ID" => ["required"],
            "NomIdentificatiu" => ["required"]
        ];
    }

    public function createValidatorUpdate(): array
    {
        return [
            "NomIdentificatiu" => ["required"]
        ];
    }
}
