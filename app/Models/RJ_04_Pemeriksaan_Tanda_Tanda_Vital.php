<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_04_Pemeriksaan_Tanda_Tanda_Vital extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'rj_04_pemeriksaan_tanda_tanda_vital';
    protected $guarded = [];
}
