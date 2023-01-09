<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traduccio extends Model
{
    use HasFactory;

    protected $table = 'Traduccions';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
