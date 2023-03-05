<?php

use App\Http\Controllers\AllotjamentController;
use App\Http\Controllers\ComentariController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\ImatgeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MunicipiController;
use App\Http\Controllers\ServeisAllotjamentController;
use App\Http\Controllers\TipusServeiController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TipusVacancesController;
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
    Route::post("/taula", [TraduccioController::class, "insertTraduccioTaula"])->middleware('token');
    Route::put("/", [TraduccioController::class, "updateTraduccio"])->middleware('token');
    Route::delete("/", [TraduccioController::class, "deleteTraduccio"])->middleware('token');
});

Route::group(["prefix"=>"comentari"], function() {
    Route::get("/", [ComentariController::class, "getComentaris"]);
    Route::get("/{id}", [ComentariController::class, "getComentari"]);
    Route::post("/", [ComentariController::class, "insertComentari"])->middleware('token');
    Route::put("/", [ComentariController::class, "updateComentari"])->middleware('token');
    Route::delete("/", [ComentariController::class, "deleteComentari"])->middleware('token');
});

Route::group(["prefix"=>"tipusservei"], function() {
    Route::get("/", [TipusServeiController::class, "getTipusServeis"]);
    Route::get("/{id}", [TipusServeiController::class, "getTipusServei"]);
    Route::post("/", [TipusServeiController::class, "insertTipusServei"])->middleware('token');
    Route::put("/", [TipusServeiController::class, "updateTipusServei"])->middleware('token');
    Route::delete("/", [TipusServeiController::class, "deleteTipusServei"])->middleware('token');
});

Route::group(["prefix"=>"idioma"], function() {
    Route::get("/", [IdiomaController::class, "getIdiomes"]);
    Route::get("/{id}", [IdiomaController::class, "getIdioma"]);
    Route::post("/", [IdiomaController::class, "insertIdioma"])->middleware('token');
    Route::put("/", [IdiomaController::class, "updateIdioma"])->middleware('token');
    Route::delete("/", [IdiomaController::class, "deleteIdioma"])->middleware('token');
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
    Route::post("/", [TipusCategoriaController::class, "insertTipusCategoria"])->middleware('token');
    Route::put("/", [TipusCategoriaController::class, "updateTipusCategoria"])->middleware('token');
    Route::delete("/", [TipusCategoriaController::class, "deleteTipusCategoria"])->middleware('token');
});

Route::group(["prefix"=>"tipusallotjament"], function() {
    Route::get("/", [TipusAllotjamentController::class, "getTipusAllotjaments"]);
    Route::get("/{id}", [TipusAllotjamentController::class, "getTipusAllotjament"]);
    Route::post("/", [TipusAllotjamentController::class, "insertTipusAllotjament"])->middleware('token');
    Route::put("/", [TipusAllotjamentController::class, "updateTipusAllotjament"])->middleware('token');
    Route::delete("/", [TipusAllotjamentController::class, "deleteTipusAllotjament"])->middleware('token');


});

Route::group(["prefix"=>"reserva"], function() {
    Route::get("/", [ReservaController::class, "getReserves"])->middleware('token');
    Route::get("/{id}", [ReservaController::class, "getReserva"])->middleware('token');
    Route::get("/allotjament/{allotjamentID}", [ReservaController::class, "getReservesAllotjament"])->middleware('token');
    Route::post("/", [ReservaController::class, "insertReserva"])->middleware('token');
    Route::put("/", [ReservaController::class, "updateReserva"])->middleware('token');
    Route::delete("/", [ReservaController::class, "deleteReserva"])->middleware('token');
});

Route::group(["prefix"=>"login"], function() {
    Route::post('', [LoginController::class, "login"]);
});

Route::group(["prefix"=>"logout"], function() {
    Route::post('', [LogoutController::class, "logout"])->middleware('token');
});

Route::group(["prefix"=>"estatsreserva"], function() {
    Route::get("/", [EstatsReservaController::class, "getEstatsReserves"]);
    Route::get("/{id}", [EstatsReservaController::class, "getEstatsReserva"]);
});

Route::group(["prefix"=>"rol"], function() {
    Route::get("/", [RolController::class, "getRols"]);
    Route::get("/{id}", [RolController::class, "getRol"]);
});

Route::group(["prefix"=>"usuari"], function() {
    Route::get("/", [UsuariController::class, "getUsuaris"])->middleware('token');
    Route::get("/{id}", [UsuariController::class, "getUsuari"])->middleware('token');
    Route::post("/", [UsuariController::class, "insertUsuari"]);
    Route::put("/", [UsuariController::class, "updateUsuari"])->middleware('token');
    Route::delete("/", [UsuariController::class, "deleteUsuari"])->middleware('token');
});

Route::group(["prefix"=>"imatge"], function() {
    Route::get("/", [ImatgeController::class, "getImatges"]);
    Route::get("/allotjament/{idAllotjament}", [ImatgeController::class, "getImatgesAllotjament"]);
    Route::get("/{id}", [ImatgeController::class, "getImatge"]);
    Route::post("/", [ImatgeController::class, "insertImatge"])->middleware('token');
    Route::put("/", [ImatgeController::class, "updateImatge"])->middleware('token');
    Route::delete("/", [ImatgeController::class, "deleteImatge"])->middleware('token');
});

Route::group(["prefix"=>"municipi"], function() {
    Route::get("/", [MunicipiController::class, "getMunicipis"]);
    Route::get("/{id}", [MunicipiController::class, "getMunicipi"]);
    Route::post("/", [MunicipiController::class, "insertMunicipi"])->middleware('token');
    Route::put("/", [MunicipiController::class, "updateMunicipi"])->middleware('token');
    Route::delete("/", [MunicipiController::class, "deleteMunicipi"])->middleware('token');
});

Route::group(["prefix"=>"tipusvacances"], function() {
    Route::get("/", [TipusVacancesController::class, "getTipusVacances"]);
    Route::get("/{id}", [TipusVacancesController::class, "getTipusVacance"]);
    Route::post("/", [TipusVacancesController::class, "insertTipusVacance"])->middleware('token');
    Route::put("/", [TipusVacancesController::class, "updateTipusVacance"])->middleware('token');
    Route::delete("/", [TipusVacancesController::class, "deleteTipusVacance"])->middleware('token');
});

Route::group(["prefix"=>"serveisallotjament"], function() {
    Route::get("/", [ServeisAllotjamentController::class, "getServeisAllotjament"]);
    Route::get("/{id}", [ServeisAllotjamentController::class, "getServeiAllotjament"]);
    Route::post("/", [ServeisAllotjamentController::class, "insertServeiAllotjament"])->middleware('token');
    Route::delete("/", [ServeisAllotjamentController::class, "deleteServeiAllotjament"])->middleware('token');
});
