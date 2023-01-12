<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allotjament extends Model
{
    use HasFactory;

    protected $table = 'Allotjaments';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $with = ["traduccions"];

    public function traduccions()
    {
        return $this->hasMany(Traduccio::class,"AllotjamentsID", "ID");
    }
}
