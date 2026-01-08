<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_10_Radiologi extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_10_radiologi';
    protected $guarded = [];
}
