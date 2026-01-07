<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_15_Questionnaire_Response_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_15_questionnaire_response_log';
    // public $timestamps = true;
    protected $guarded = [];
}
