<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipusServei extends Model
{
    use HasFactory;

    protected $table = 'TipusServeis';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $with = ["traduccions"];

    public function traduccions()
    {
        return $this->hasMany(Traduccio::class,"TipusServeisID", "ID");
    }
}
