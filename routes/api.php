<?php

use App\Http\Controllers\AllotjamentController;
use App\Http\Controllers\TraduccioController;
use App\Http\Controllers\TarifaController;
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

Route::group(["prefix"=>"tarifa"], function() {
    Route::get("/", [TarifaController::class, "getTarifes"]);
    Route::get("/{id}", [TarifaController::class, "getTarifa"]);
    Route::post("/", [TarifaController::class, "insertTarifa"]);
    Route::put("/", [TarifaController::class, "updateTarifa"]);
    Route::delete("/", [TarifaController::class, "deleteTarifa"]);
});
