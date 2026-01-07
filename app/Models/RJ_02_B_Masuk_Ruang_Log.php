<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_02_B_Masuk_Ruang_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_02_b_masuk_ruang_log';
    protected $guarded = [];
}
