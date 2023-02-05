<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipusVacances extends Model
{
    use HasFactory;

    protected $table = 'TipusVacances';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
