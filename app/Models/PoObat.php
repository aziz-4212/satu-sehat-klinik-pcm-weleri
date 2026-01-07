<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PoObat extends Model
{
    protected $table = 'po_obat';
    protected $guarded = [];

    // Relasi ke Detail (One to Many)
    public function details()
    {
        return $this->hasMany(PoObatDetail::class, 'po_obat_id');
    }
}