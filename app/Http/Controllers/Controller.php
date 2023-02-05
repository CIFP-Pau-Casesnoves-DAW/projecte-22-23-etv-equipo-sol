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
 * @OA\Server(url="http://localhost:81/allotjamentssol/public/")
 *
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * in="header",
 * name="bearerAuth",
 * type="http",
 * scheme="bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
