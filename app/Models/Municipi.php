<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipi extends Model
{
    use HasFactory;

    protected $table = 'Municipis';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
