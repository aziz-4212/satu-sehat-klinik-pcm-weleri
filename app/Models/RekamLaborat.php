<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamLaborat extends Model
{
    protected $table = "rekam_laborat";
    protected $guarded = [];

    public function master_laboratorium(){
        return $this->belongsTo(MasterLaboratorium::class,'laborat_id','id');
    }
}
