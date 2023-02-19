<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="Projecte Allotjaments Sol", version="1.0",
 * description="REST API. Projecte Allotjaments Sol. DAW Client i servidor. Per utilitzar les cridades que necesiten token primer
  s'haura de fer login amb un usuari per obtenir el token i despres utilitzar aquest token a la part on posa Authorize. Per
  mes informacio, obtenir un usuari o altres peticions utilitzar el link de Contact Equip Sol o mandar un mail a jaumefullana@paucasesnovescifp.cat",
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
