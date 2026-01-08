<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_15_Questionnaire_Response extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_15_questionnaire_response';
    // public $timestamps = true;
    protected $guarded = [];
}
