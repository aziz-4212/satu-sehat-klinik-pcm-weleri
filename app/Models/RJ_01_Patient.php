<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_01_Patient extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_01_patient';
    protected $guarded = [];
}
