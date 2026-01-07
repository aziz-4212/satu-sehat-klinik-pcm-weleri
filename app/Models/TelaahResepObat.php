<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelaahResepObat extends Model
{
    use HasFactory;

    protected $table = 'telaah_resep_obat';

    protected $guarded = [];

    protected $fillable = [
        'pengeluaran_id',
        'apoteker_id',
        'admin_kejelasan_tulisan',
        'admin_identitas_pasien',
        'admin_nama_dokter',
        'admin_tanggal_resep',
        'admin_ruangan',
        'admin_riwayat_alergi',

        'farma_tepat_nama_obat',
        'farma_campuran_obat_stabil',

        'klinis_tepat_dosis',
        'klinis_tepat_rute',
        'klinis_interaksi_obat',
        'klinis_duplikasi_obat',

        'telaah_tepat_identitas',
        'telaah_tepat_obat',
        'telaah_tepat_dosis',
        'telaah_tepat_rute',
        'telaah_tepat_waktu',

        'efek_samping',
        'penerima_nama',
        'penerima_telp',
        'edukasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengeluaran()
    {
        return $this->belongsTo(PengeluaranObat::class, 'pengeluaran_id');
    }
}
