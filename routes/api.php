<?php

use App\Http\Controllers\AllotjamentController;
use App\Http\Controllers\ComentariController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\TipusServeiController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TraduccioController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\TipusCategoriaController;
use App\Http\Controllers\TipusAllotjamentController;
use Illuminate\Http\Request;
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
    Route::post("/", [AllotjamentController::class, "insertAllotjament"]);
    Route::put("/", [AllotjamentController::class, "updateAllotjament"]);
    Route::delete("/", [AllotjamentController::class, "deleteAllotjament"]);
});

Route::group(["prefix"=>"traduccio"], function() {
    Route::get("/", [TraduccioController::class, "getTraduccions"]);
    Route::get("/{id}", [TraduccioController::class, "getTraduccio"]);
    Route::post("/", [TraduccioController::class, "insertTraduccio"]);
    Route::put("/", [TraduccioController::class, "updateTraduccio"]);
    Route::delete("/", [TraduccioController::class, "deleteTraduccio"]);
});

Route::group(["prefix"=>"comentari"], function() {
    Route::get("/", [ComentariController::class, "getComentaris"]);
    Route::get("/{id}", [ComentariController::class, "getComentari"]);
    Route::post("/", [ComentariController::class, "insertComentari"]);
    Route::put("/", [ComentariController::class, "updateComentari"]);
    Route::delete("/", [ComentariController::class, "deleteComentari"]);
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
    Route::post("/", [TarifaController::class, "insertTarifa"]);
    Route::put("/", [TarifaController::class, "updateTarifa"]);
    Route::delete("/", [TarifaController::class, "deleteTarifa"]);
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
    Route::get("/", [ReservaController::class, "getReserves"]);
    Route::get("/{id}", [ReservaController::class, "getReserva"]);
    Route::post("/", [ReservaController::class, "insertReserva"]);
    Route::put("/", [ReservaController::class, "updateReserva"]);
    Route::delete("/", [ReservaController::class, "deleteReserva"]);
});
