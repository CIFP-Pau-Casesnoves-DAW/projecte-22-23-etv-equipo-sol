<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $table = 'Tarifes';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
