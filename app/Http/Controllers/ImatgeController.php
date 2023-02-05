<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Imatge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImatgeController extends Controller
{
    /**
    * @OA\Get(
    * path="/api/imatge",
    * tags={"Imatges"},
    * summary="Mostrar totes les imatges.",
    * @OA\Response(
    * response=200,
    * description="Mostrar totes les imatges.",
    *          @OA\JsonContent(
    *          @OA\Property(property="Status", type="string", example="200"),
    *          @OA\Property(property="Result",type="object")
    *  ),
    *  ),
    * )
    */
    public function getImatges(){
        $Imatges = Imatge::all();
        return response()->json(["Status" => "Success","Result" => $Imatges], 200);
    }

    /**
    *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/imatge/{id}",
     *     tags={"Imatges"},
     *     summary="Mostrar una imatge",
     *     @OA\Parameter(
     *         description="Id de la imatge",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació de la imatge.",
     *          @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="200"),
     *          @OA\Property(property="Result",type="object")
     *           ),
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Hi ha un error.",
     *         @OA\JsonContent(
     *          @OA\Property(property="Status", type="string", example="Error"),
     *          @OA\Property(property="Result",type="string", example="imatge no trobada")
     *           ),
     *     )
     * )
     */
    public function getImatge($id){
        $imatge = Imatge::findOrFail($id);
        return response()->json(["Status" => "Success","Result" => $imatge], 200);
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/imatge",
     *    tags={"Imatges"},
     *    summary="Crea una imatge",
     *    description="Crea una nova imatge.",
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="URL", type="varchar", example="https://rutaimatge/"),
     *           @OA\Property(property="EsPrincipal", type="boolean", example="false"),
     *           @OA\Property(property="Descripcio", type="varchar", example="casa rural amb vistea a muntanya"),
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
     *         @OA\Property(property="Result",type="string", example="Atribut URL requerit")
     *          ),
     *       )
     *  )
     */
    public function insertImatge(Request $request){
        $imatge = new Imatge();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $imatge->URL = $request->URL;
        $imatge->EsPrincipal = $request->EsPrincipal;
        $imatge->Descripcio = $request->Descripcio;

        if ($imatge->save()) {
            return response()->json(['Status' => 'Success','Result' => $imatge], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error guardant'], 400);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/imatge",
     *    tags={"Imatges"},
     *    summary="Modifica una imatge",
     *    description="Modifica una imatge.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="2"),
     *           @OA\Property(property="URL", type="varchar", example="https://rutaimatge/"),
     *           @OA\Property(property="EsPrincipal", type="boolean", example="false"),
     *           @OA\Property(property="Descripcio", type="varchar", example="casa rural amb vistea a muntanya"),
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
     *         @OA\Property(property="Status", type="integer", example="Error"),
     *         @OA\Property(property="Result",type="string", example="Atribut imatge requerit")
     *         ),
     *      )
     *  )
     */
    public function updateImatge(Request $request){
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $imatge=Imatge::findOrFail($request->ID);
        $validator = $this->createValidatorUpdate();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()){
            return response()->json(["Status" => "Error","Result"=>$isValid->errors()], 400);
        }

        $imatge->URL = $request->URL;
        $imatge->EsPrincipal = $request->EsPrincipal;
        $imatge->Descripcio = $request->Descripcio;

        if ($imatge->save()) {
            return response()->json(['Status' => 'Success','Result' => $imatge], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error actualitzant'], 400);
        }
    }

     /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/imatge",
     *    tags={"Imatges"},
     *    summary="Esborra una imatge",
     *    description="Esborra una imatge.",
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
    public function deleteImatge(Request $request){
        if ($request->ID == null || $request->ID < 0) {
            return response()->json(["Status" => "Error","Result"=>"Incorrect ID"], 400);
        }

        $imatge=Imatge::findOrFail($request->ID);

        if ($isDeleted = $imatge->delete()) {
            return response()->json(['Status' => 'Success','Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error','Result' => 'Error borrant'], 400);
        }
    }

    public function createValidator(): array
    {
        return [
            "URL" => ["required"],
            "EsPrincipal" => ["required"],
            "Descripcio" => ["required"]];
    }

    public function createValidatorUpdate(): array
    {
        return [
            "URL" => ["required"],
            "EsPrincipal" => ["required"],
            "Descripcio" => ["required"]];
    }
}