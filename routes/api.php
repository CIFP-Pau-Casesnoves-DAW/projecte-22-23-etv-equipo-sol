<?php

use App\Http\Controllers\AllotjamentController;
use App\Http\Controllers\ComentariController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TipusServeiController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TraduccioController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\EstatsReservaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuariController;
use App\Http\Controllers\TipusCategoriaController;
use App\Http\Controllers\TipusAllotjamentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group(["prefix"=>"allotjament"], function() {
    Route::get("/", [AllotjamentController::class, "getAllotjaments"]);
    Route::get("/{id}", [AllotjamentController::class, "getAllotjament"]);
    Route::post("/", [AllotjamentController::class, "insertAllotjament"])->middleware('token');
    Route::put("/", [AllotjamentController::class, "updateAllotjament"])->middleware('token');
    Route::delete("/", [AllotjamentController::class, "deleteAllotjament"])->middleware('token');
});


Route::group(["prefix"=>"traduccio"], function() {
    Route::get("/", [TraduccioController::class, "getTraduccions"]);
    Route::get("/{id}", [TraduccioController::class, "getTraduccio"]);
    Route::post("/", [TraduccioController::class, "insertTraduccio"])->middleware('token');
    Route::put("/", [TraduccioController::class, "updateTraduccio"])->middleware('token');
    Route::delete("/", [TraduccioController::class, "deleteTraduccio"])->middleware('token');
});

Route::group(["prefix"=>"comentari"], function() {
    Route::get("/", [ComentariController::class, "getComentaris"]);
    Route::get("/{id}", [ComentariController::class, "getComentari"]);
    Route::post("/", [ComentariController::class, "insertComentari"]);
    Route::put("/", [ComentariController::class, "updateComentari"])->middleware('token');
    Route::delete("/", [ComentariController::class, "deleteComentari"])->middleware('token');
});

Route::group(["prefix"=>"tipusservei"], function() {
    Route::get("/", [TipusServeiController::class, "getTipusServeis"]);
    Route::get("/{id}", [TipusServeiController::class, "getTipusServei"]);
});

Route::group(["prefix"=>"idioma"], function() {
    Route::get("/", [IdiomaController::class, "getIdiomes"]);
    Route::get("/{id}", [IdiomaController::class, "getIdioma"]);
});

Route::group(["prefix"=>"tarifa"], function() {
    Route::get("/", [TarifaController::class, "getTarifes"]);
    Route::get("/{id}", [TarifaController::class, "getTarifa"]);
    Route::post("/", [TarifaController::class, "insertTarifa"])->middleware('token');
    Route::put("/", [TarifaController::class, "updateTarifa"])->middleware('token');
    Route::delete("/", [TarifaController::class, "deleteTarifa"])->middleware('token');
});

Route::group(["prefix"=>"tipuscategoria"], function() {
    Route::get("/", [TipusCategoriaController::class, "getTipusCategories"]);
    Route::get("/{id}", [TipusCategoriaController::class, "getTipusCategoria"]);
});

Route::group(["prefix"=>"tipusallotjament"], function() {
    Route::get("/", [TipusAllotjamentController::class, "getTipusAllotjaments"]);
    Route::get("/{id}", [TipusAllotjamentController::class, "getTipusAllotjament"]);
});

Route::group(["prefix"=>"reserva"], function() {
    Route::get("/", [ReservaController::class, "getReserves"])->middleware('token');
    Route::get("/{id}", [ReservaController::class, "getReserva"])->middleware('token');
    Route::post("/", [ReservaController::class, "insertReserva"])->middleware('token');
    Route::put("/", [ReservaController::class, "updateReserva"])->middleware('token');
    Route::delete("/", [ReservaController::class, "deleteReserva"])->middleware('token');
});

Route::group(["prefix"=>"login"], function() {
    Route::post('', [LoginController::class, "login"]);
});

Route::group(["prefix"=>"estatsReserva"], function() {
    Route::get("/", [EstatsReservaController::class, "getEstatsReserves"]);
    Route::get("/{id}", [EstatsReservaController::class, "getEstatsReserva"]);
});

Route::group(["prefix"=>"rol"], function() {
    Route::get("/", [RolController::class, "getRols"]);
    Route::get("/{id}", [RolController::class, "getRol"]);
    Route::post("/", [RolController::class, "insertRol"]);
    Route::put("/", [RolController::class, "updateRol"]);
    Route::delete("/", [RolController::class, "deleteRol"]);
});

Route::group(["prefix"=>"usuari"], function() {
    Route::get("/", [UsuariController::class, "getUsuaris"]);
    Route::get("/{id}", [UsuariController::class, "getUsuari"]);
    Route::post("/", [UsuariController::class, "insertUsuari"]);
    Route::put("/", [UsuariController::class, "updateUsuari"]);
    Route::delete("/", [UsuariController::class, "deleteUsuari"]);
});

