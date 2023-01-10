<?php

use App\Http\Controllers\AllotjamentController;
use App\Http\Controllers\ComentariController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\TipusServeiController;
use App\Http\Controllers\TraduccioController;
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
