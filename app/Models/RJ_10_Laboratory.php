<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RJ_10_Laboratory extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'RJ_10_laboratory';
    protected $guarded = [];
}
