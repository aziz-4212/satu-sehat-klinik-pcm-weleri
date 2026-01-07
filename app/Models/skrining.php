<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skrining extends Model
{
    protected $table = 'skrining'; // sesuaikan dengan nama tabel kamu

    protected $fillable = [
    'rekam_id',
    'asal_rujukan',
    'tanggal_kunjungan',
    'jam_datang',
    'pernafasan',
    'gug_a',
    'gug_b',
    'resiko_jatuh',
    'nyeri_dada',
    'skala_nyeri',
    'batuk',
    'keputusan',
];

public $timestamps = false;


    public function rekam()
    {
        return $this->belongsTo(Rekam::class);
    }
}
