<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_06_Riwayat_Perjalanan_Penyakit_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'rj_06_riwayat_perjalanan_penyakit_log';
    protected $guarded = [];
}
