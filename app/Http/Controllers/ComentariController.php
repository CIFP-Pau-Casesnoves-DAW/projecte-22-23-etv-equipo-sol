<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Comentari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComentariController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/comentari",
     *      tags={"Comentaris"},
     *      summary="Mostrar tots els comentaris.",
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
    public function getComentaris(){
        $comentaris = Comentari::all();
        return response()->json(["Status" => "Success","Result" => $comentaris], 200);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/comentari/{id}",
     *     tags={"Comentaris"},
     *     summary="Mostrar el comentari al qual correspon la id passada per la url",
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
    public function getComentari($id){
        $comentari = Comentari::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $comentari], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/comentari",
     *    tags={"Comentaris"},
     *    summary="Crea un nou comentari",
     *    description="Crea un nou comentari.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="Comentari", type="string", format="string", example="Aquest allotjament es un 10"),
     *           @OA\Property(property="Valoracio", type="number", format="number", example="5"),
     *           @OA\Property(property="DataCreacio", type="date", format="date", example="2022-03-21"),
     *           @OA\Property(property="AllotjamentsID ", type="number", format="number", example="1")
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
    public function insertComentari(Request $request){
        $comentari = new Comentari();

        $validator = $this->createInsertValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $comentari->Comentari = $request->Comentari;
        $comentari->Valoracio = $request->Valoracio;
        $comentari->DataCreacio = $request->DataCreacio;
        $comentari->AllotjamentsID = $request->AllotjamentsID;
        $comentari->UsuarisID = $request->DadesUsuari->ID;

        if ($comentari->save()) {
            return response()->json(['Status' => 'Success','Result' => $comentari], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/comentari",
     *    tags={"Comentaris"},
     *    summary="Modifica un comentari",
     *    description="Modifica un comentari.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="1"),
     *           @OA\Property(property="Comentari", type="string", format="string", example="Aquest allotjament no es per tant"),
     *           @OA\Property(property="Valoracio", type="number", format="number", example="3")
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
    public function updateComentari(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $comentari=Comentari::findOrFail($request->ID);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $comentari->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 400);
        }

        $validator = $this->createUpdateValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $comentari->Comentari = $request->Comentari;
        $comentari->Valoracio = $request->Valoracio;

        if ($comentari->save()) {
            return response()->json(['Status' => 'Success','Result' => $comentari], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/comentari",
     *    tags={"Comentaris"},
     *    summary="Esborra un comentari",
     *    description="Esborra un comentari.",
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
    public function deleteComentari(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $comentari=Comentari::findOrFail($request->ID);

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $comentari->UsuarisID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 400);
        }

        if ($isDeleted = $comentari->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createInsertValidator(): array
    {
        return [
            "Comentari" => ["required", "max:500"],
            "Valoracio" => ["required"],
            "DataCreacio" => ["required"],
            "AllotjamentsID" => ["required"]];
    }

    public function createUpdateValidator(): array
    {
        return [
            "Comentari" => ["required", "max:500"],
            "Valoracio" => ["required"]];
    }
}
