<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';

    // PASTIKAN SEMUA KOLOM INI ADA DI SINI
    protected $fillable = [
        'obat_id',
        'tanggal',
        'masuk',
        'keluar',
        'sisa_stok'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}