<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_02_B_Masuk_Ruang extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_02_b_masuk_ruang';
    protected $guarded = [];
}
