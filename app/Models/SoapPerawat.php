<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoapPerawat extends Model
{
    protected $table = 'soap_perawat';
    protected $guarded = [];

    // INI KUNCINYA! Agar JSON di database terbaca sebagai Array di View
    protected $casts = [
        'diagnosa' => 'array',
        'tindakan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}