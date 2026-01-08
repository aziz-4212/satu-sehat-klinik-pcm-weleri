<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_02_A_Kunjungan_Baru extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_02_a_kunjungan_baru';
    // public $timestamps = false;
    protected $guarded = [];

    public function registrasi_pasien()
    {
        return $this->belongsTo(RegistrasiPasien::class, 'noreg', 'NOREG');
    }

    public function sep()
    {
        return $this->belongsTo(RegistrasiPasien::class, 'noreg', 'NOREG');
    }

    public function bpjs_insert_sep()
    {
        return $this->belongsTo(BpjsSepManual::class, 'noreg', 'noreg');
    }

    public function checkin()
    {
        return $this->belongsTo(CheckinPoli::class, 'noreg', 'noreg');
    }
}
