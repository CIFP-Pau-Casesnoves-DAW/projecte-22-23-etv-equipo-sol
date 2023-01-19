<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipusCategoria extends Model
{
    use HasFactory;

    protected $table = 'TipusCategories';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
