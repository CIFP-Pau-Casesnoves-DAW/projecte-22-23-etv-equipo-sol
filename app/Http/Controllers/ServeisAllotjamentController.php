<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Allotjament;
use App\Models\ServeisAllotjament;
use App\Models\TipusServei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServeisAllotjamentController extends Controller
{
    /**
    * @OA\Get(
    * path="/api/serveisallotjament",
    * tags={"Serveis Allotjament"},
    * summary="Mostrar tots els serveis d'allotjament.",
    * @OA\Response(
    * response=200,
    * description="Success",
    *          @OA\JsonContent(
    *              @OA\Property(property="Status", type="string", example="Success"),
    *              @OA\Property(property="Result",type="object")
    *         )
    * )
    * )
    */
    public function getServeisAllotjament(){
        $serveisAllotjament = ServeisAllotjament::all();
        return response()->json(["Status" => "Success","Result" => $serveisAllotjament], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/serveisallotjament/{idAllotjament}",
     *     tags={"Serveis Allotjament"},
     *     summary="Mostrar els serveis d'un allotjament",
     *     @OA\Parameter(
     *         description="Id de l'allotjament",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tots els serveis d'un allotjament.",
     *          @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="200"),
     *          @OA\Property(property="Result",type="object")
     *           ),
     *      ),
     * )
     */
    public function getServeiAllotjament($idAllotjament){
        $serveisAllotjament = ServeisAllotjament::where("AllotjamentsID","=",$idAllotjament)->get();
        return response()->json(["Status" => "Success","Result" => $serveisAllotjament], 200);
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/serveisallotjament",
     *    tags={"Serveis Allotjament"},
     *    summary="Crea un servei d'allotjament",
     *    description="Crea un servei d'allotjament.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="TipusServeisID", type="number", format="number", example="1234"),
     *           @OA\Property(property="AllotjamentsID", type="varchar", example="Nom del servei"),
     *
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
     *         @OA\Property(property="Result",type="string", example="Atribut numero de registre requerit")
     *          ),
     *       )
     *  )
     */
    public function insertServeiAllotjament(Request $request){
        $serveiAllotjament = new ServeisAllotjament();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $allotjament=Allotjament::find($request->AllotjamentsID);

        if ($allotjament == null){
            return response()->json(["Status" => "Error","No hi ha cap allotjamen amb aqueixa id"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $allotjament->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $tipusServei=TipusServei::find($request->ID);

        if ($tipusServei == null){
            return response()->json(["Status" => "Error","Result"=> "No existeix cap tipusServei amb aquesta id"], 400);
        }

        $serveiAllotjament->TipusServeisID = $request->TipusServeisID;
        $serveiAllotjament->AllotjamentsID = $request->AllotjamentsID;

        if ($serveiAllotjament->save()) {
            return response()->json(['Status' => 'Success','Result' => $serveiAllotjament], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }


     /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/serveisallotjament",
     *    tags={"Serveis Allotjament"},
     *    summary="Esborra un servei d'allotjament",
     *    description="Esborra un servei d'allotjament.",
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
    public function deleteServeiAllotjament(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $serveiAllotjament=ServeisAllotjament::find($request->ID);

        if ($serveiAllotjament == null){
            return response()->json(["Status" => "Error","Result"=>"No existeix cap serveiAllotjament amb aquesta id"], 400);
        }

        $allotjament=Allotjament::findOrFail($serveiAllotjament->AllotjamentsID);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $allotjament->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        if ($isDeleted = $serveiAllotjament->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "TipusServeisID" => ["required"],
            "AllotjamentsID" => ["required"]];
    }
}
