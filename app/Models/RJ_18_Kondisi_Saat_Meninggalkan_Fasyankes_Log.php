<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_18_Kondisi_Saat_Meninggalkan_Fasyankes_Log extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'rj_18_kondisi_saat_meninggalkan_fasyankes_log';
    protected $guarded = [];
}
