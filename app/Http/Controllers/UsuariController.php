<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ControllersHelper;
use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuariController extends Controller
{
    //GET de tot

    /**
     * @OA\Get(
     * path="/api/usuari",
     * tags={"Usuaris"},
     * summary="Mostrar tots els usuaris.",
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Mostrar tots els usuari."
     * ),
     * @OA\Response(
     * response=400,
     * description="Hi ha un error."
     * ),
     * )
     */
    public function getUsuaris(Request $request)
    {
        if ($request->DadesUsuari->RolsID != 3) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $usuaris = Usuari::all();
        return response()->json(["Status" => "Success", "Result" => $usuaris], 200);
    }


    //GET de una ID

    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/usuari/{id}",
     *     tags={"Usuaris"},
     *     summary="Mostrar un usuari",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Id de l'usuari",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació de l'usuari.",
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
     *          @OA\Property(property="data",type="string", example="Usuari no trobat")
     *           ),
     *     )
     * )
     */
    public function getUsuari($id, Request $request)
    {
        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $id) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $usuari = Usuari::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $usuari], 200);
    }

    //INSERT

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *    path="/api/usuari",
     *    tags={"Usuaris"},
     *    summary="Crea un usuari",
     *    description="Crea una nou usuari.",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="Nom", type="string", format="string", example="Pedro"),
     *           @OA\Property(property="Llinatges", type="string", format="string", example="Gimenez Santos"),
     *           @OA\Property(property="Contrasenya", type="string", format="string", example="abcd1234"),
     *           @OA\Property(property="CorreuElectronic", type="string", format="string", example="pedro@gmail.com"),
     *           @OA\Property(property="DNI", type="string", format="string", example="12341234Q"),
     *           @OA\Property(property="Telefon", type="string", format="string", example="971123123"),
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
     *         @OA\Property(property="data",type="string", example="Atribut Llinatges requerit")
     *          ),
     *       )
     *  )
     */
    public function insertUsuari(Request $request)
    {
        $usuari = new Usuari();

        $validator = $this->createValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }
        $usuari->Nom = $request->Nom;
        $usuari->Llinatges = $request->Llinatges;
        $usuari->Contrasenya = $request->Contrasenya;
        $usuari->CorreuElectronic = $request->CorreuElectronic;
        $usuari->DNI = $request->DNI;
        $usuari->Telefon = $request->Telefon;
        $usuari->RolsID = 2;


        if ($usuari->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $usuari], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error guardant'], 400);
        }
    }

    //UPDATE

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Put(
     *    path="/api/usuari",
     *    tags={"Usuaris"},
     *    summary="Modifica un usuari",
     *    description="Modifica un usuari.",
     *    security={{"bearerAuth":{}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           @OA\Property(property="ID", type="number", format="number", example="10"),
     *           @OA\Property(property="Nom", type="string", format="string", example="Pedro"),
     *           @OA\Property(property="Llinatges", type="string", format="string", example="Gimenez Santos"),
     *           @OA\Property(property="Contrasenya", type="string", format="string", example="abcd1234"),
     *           @OA\Property(property="CorreuElectronic", type="string", format="string", example="pedro@gmail.com"),
     *           @OA\Property(property="DNI", type="string", format="string", example="12341234Q"),
     *           @OA\Property(property="Telefon", type="string", format="string", example="971123133"),
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
     *         @OA\Property(property="data",type="string", example="Atribut DNI requerit")
     *         ),
     *      )
     *  )
     */
    public function updateUsuari(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $request->ID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $usuari = Usuari::findOrFail($request->ID);
        $validator = $this->updateValidator();
        $messages = ControllersHelper::createValidatorMessages();

        $isValid = Validator::make($request->all(), $validator, $messages);

        if ($isValid->fails()) {
            return response()->json(["Status" => "Error", "Result" => $isValid->errors()], 400);
        }

        $usuari->Nom = $request->Nom;
        $usuari->Llinatges = $request->Llinatges;
        $usuari->Contrasenya = $request->Contrasenya;
        $usuari->CorreuElectronic = $request->CorreuElectronic;
        $usuari->DNI = $request->DNI;
        $usuari->Telefon = $request->Telefon;

        if ($usuari->save()) {
            return response()->json(['Status' => 'Success', 'Result' => $usuari], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error actualitzant'], 400);
        }
    }

    //DELETE
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\Delete(
     *    path="/api/usuari",
     *    tags={"Usuaris"},
     *    summary="Esborra un usuari",
     *    description="Esborra un usuari.",
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
    public function deleteUsuari(Request $request)
    {
        if ($request->ID == null || $request->ID < 1) {
            return response()->json(["Status" => "Error", "Result" => "Incorrect ID"], 400);
        }

        if ($request->DadesUsuari->RolsID != 3 && $request->DadesUsuari->ID != $request->ID) {
            return response()->json(["Status" => "Error", "Result" => "Privilegis insuficients."], 401);
        }

        $usuari = Usuari::findOrFail($request->ID);

        if ($isDeleted = $usuari->delete()) {
            return response()->json(['Status' => 'Success', 'Result' => $isDeleted], 200);
        } else {
            return response()->json(['Status' => 'Error', 'Result' => 'Error borrant'], 400);
        }
    }

    //VALIDADOR

    public function createValidator(): array
    {
        return [
            "Nom" => ["required", Rule::unique('posts', 'Nom')],
            "Llinatges" => ["required"],
            "Contrasenya" => ["required"],
            "CorreuElectronic" => ["required"],
            "DNI" => ["required"],
            "Telefon" => ["required"]
        ];
    }
    public function updateValidator(): array
    {
        return [
            "Nom" => ["required"],
            "Llinatges" => ["required"],
            "Contrasenya" => ["required"],
            "CorreuElectronic" => ["required"],
            "DNI" => ["required"],
            "Telefon" => ["required"]
        ];
    }
}
