<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_15_Medication_Request_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_15_medication_request_log';
    // public $timestamps = true;
    protected $guarded = [];
}
