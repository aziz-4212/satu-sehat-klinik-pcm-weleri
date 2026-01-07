<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacikanDetail extends Model
{
    protected $table = "racikan_detail";
    protected $guarded = [];

    public function racikan()
    {
        return $this->belongsTo(RacikanObat::class, 'racikan_id');
    }
}
