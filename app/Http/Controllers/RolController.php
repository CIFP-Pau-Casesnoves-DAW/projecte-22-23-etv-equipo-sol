<?php

namespace App\Http\Controllers;

use App\Models\Rol;

class RolController extends Controller
{
    //GET de tot

    /**
     * @OA\Get(
     * path="/api/rol",
     * tags={"Rols"},
     * summary="Mostrar tots els rols",
     * @OA\Response(
     * response=200,
     * description="Mostrar tots els rols."
     * ),
     * @OA\Response(
     * response=400,
     * description="Hi ha un error."
     * ),
     * )
     */
    public function getRols()
    {
        $Rols = Rol::all();
        return response()->json(["Status" => "Success", "Result" => $Rols], 200);
    }


    //GET de una ID

    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/rol/{id}",
     *     tags={"Rols"},
     *     summary="Mostrar un rol",
     *     @OA\Parameter(
     *         description="Id del rol",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informació del rol",
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
     *          @OA\Property(property="data",type="string", example="Rol no trobat")
     *           ),
     *     )
     * )
     */
    public function getRol($id)
    {
        $Rol = Rol::findOrFail($id);
        return response()->json(["Status" => "Success", "Result" => $Rol], 200);
    }

}