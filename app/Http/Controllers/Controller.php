<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="Projecte Allotjaments Sol", version="1.0",
 * description="REST API. Projecte Allotjaments Sol. DAW Client i servidor.",
 * @OA\Contact( name="Equip Sol",email="jaumefullana@paucasesnovescifp.cat")
 * )
 *
 * @OA\Server(url="http://127.0.0.1/laravel/projecte-22-23-etv-equipo-sol/public/")
 *
 * @OAS\SecurityScheme(
 * type="http",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
