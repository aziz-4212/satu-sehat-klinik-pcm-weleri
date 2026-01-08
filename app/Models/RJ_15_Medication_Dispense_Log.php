<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_15_Medication_Dispense_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_15_medication_dispense_log';
    protected $guarded = [];
}
