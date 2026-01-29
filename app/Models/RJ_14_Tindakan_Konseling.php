<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_14_Tindakan_Konseling extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'rj_14_tindakan_konseling';
    protected $guarded = [];
}
