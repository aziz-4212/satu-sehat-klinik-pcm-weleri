<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_02_A_Kunjungan_Baru_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_02_a_kunjungan_baru_log';
    protected $guarded = [];

    public function regpas()
    {
        return $this->belongsTo(RegistrasiPasien::class, 'noreg', 'NOREG');
    }
}
