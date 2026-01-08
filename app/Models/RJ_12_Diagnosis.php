<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_12_Diagnosis extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_12_diagnosis';
    protected $guarded = [];
}
