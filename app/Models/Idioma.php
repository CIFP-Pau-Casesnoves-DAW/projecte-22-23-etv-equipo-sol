<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    use HasFactory;

    protected $table = 'Idiomes';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $with = ["traduccions"];

    public function traduccions()
    {
        return $this->hasMany(Traduccio::class,"IdiomesID", "ID");
    }
}
