<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacikanObat extends Model
{
    protected $table = "racikan_obat";
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(RacikanDetail::class, 'racikan_id');
    }
}
