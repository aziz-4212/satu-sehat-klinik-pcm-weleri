<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisikoJatuhDewasa extends Model
{
    use HasFactory;

    protected $table = 'risiko_jatuh_dewasa';

    // Daftar kolom yang boleh diisi (dengan penjelasan untuk programmer)
    protected $fillable = [
        'rekam_id',
        'user_id',

        // 1. Riwayat Jatuh (Tidak=0, Ya=25)
        'skor_riwayat_jatuh', 

        // 2. Diagnosis Sekunder (Tidak=0, Ya=15)
        'skor_diagnosis_sekunder', 

        // 3. Alat Bantu (Tidak=0, Tongkat=15, Perabot=30)
        'skor_alat_bantu', 

        // 4. Terpasang Infus (Tidak=0, Ya=20)
        'skor_infus', 

        // 5. Gaya Berjalan (Normal=0, Lemah=10, Terganggu=20)
        'skor_gaya_berjalan', 

        // 6. Status Mental (Sadar=0, Lupa=15)
        'skor_status_mental', 

        // Hasil Perhitungan
        'total_skor', 
        'tingkat_risiko',
    ];
}