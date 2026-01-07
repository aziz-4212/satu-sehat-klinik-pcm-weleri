<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PoObatDetail extends Model
{
    protected $table = 'po_obat_detail';
    protected $guarded = [];

    // Relasi balik ke Header
    public function header()
    {
        return $this->belongsTo(PoObat::class, 'po_obat_id');
    }

    // Relasi ke Data Obat (Untuk ambil Nama & Kode Obat)
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id'); 
        // Pastikan Model Obat Anda namanya 'Obat'
    }
}