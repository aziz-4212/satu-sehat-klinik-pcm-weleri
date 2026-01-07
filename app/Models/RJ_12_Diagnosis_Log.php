<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_12_Diagnosis_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_12_diagnosis_log';
    protected $guarded = [];
}
