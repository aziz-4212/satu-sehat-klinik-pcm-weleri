<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_00_Organisation_Location extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'RJ_00_organisation_location';
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
