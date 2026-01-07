<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamTindakan extends Model
{
    protected $table = "rekam_tindakan";
    protected $guarded = [];

    public function tindakan(){
        return $this->belongsTo(Tindakan::class,'tindakan_id','id');
    }
}
