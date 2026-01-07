<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_01_Practitioner extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_01_practitioner';
    protected $guarded = [];
}
