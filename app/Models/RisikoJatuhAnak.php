<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisikoJatuhAnak extends Model
{
    use HasFactory;

    protected $table = 'risiko_jatuh_anak';

    // Daftar kolom yang boleh diisi (dengan penjelasan)
    protected $fillable = [
        'rekam_id',
        'user_id',

        // 1. Usia (4, 3, 2, 1)
        'skor_usia',

        // 2. Jenis Kelamin (Laki=2, Perempuan=1)
        'skor_kelamin',

        // 3. Diagnosis (4, 3, 2, 1)
        'skor_diagnosis',

        // 4. Gangguan Kognitif (3, 2, 1)
        'skor_kognitif',

        // 5. Faktor Lingkungan (4, 3, 2, 1)
        'skor_lingkungan',

        // 6. Pembedahan/Sedasi (3, 2, 1)
        'skor_operasi',

        // 7. Penggunaan Medikamentosa (3, 2, 1)
        'skor_medikasi',

        // Hasil
        'total_skor',
        'tingkat_risiko',
    ];
}