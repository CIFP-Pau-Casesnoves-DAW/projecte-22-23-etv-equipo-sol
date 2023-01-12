<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentari extends Model
{
    use HasFactory;

    protected $table = 'Comentaris';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
