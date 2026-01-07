<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_15_Medication_Obat extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_15_medication_obat';
    // public $timestamps = true;
    protected $guarded = [];
}
