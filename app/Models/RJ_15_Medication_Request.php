<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_15_Medication_Request extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_15_medication_request';
    // public $timestamps = true;
    protected $guarded = [];
}
