<?php

namespace App\Http\Helpers;

class ControllersHelper
{
    public static function createValidatorMessages() : array
    {
        return [
            "required" => "El camp :attribute es obligatori",
            "unique" => "El camp :attribute amb valor :input ja existeix",
            "max" => "El valor :input per al camp :attribute es massa llarg",
            "min" => "El valor :input per al camp :attribute es massa curt"];
    }

}
