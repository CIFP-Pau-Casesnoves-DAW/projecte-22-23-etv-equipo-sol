<?php

use App\Http\Controllers\AllotjamentController;
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
