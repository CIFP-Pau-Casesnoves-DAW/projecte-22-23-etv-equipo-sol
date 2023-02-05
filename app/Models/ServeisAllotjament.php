<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServeisAllotjament extends Model
{
    use HasFactory;

    protected $table = 'ServeisAllotjaments';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
