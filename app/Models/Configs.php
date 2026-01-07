<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'config';
    public $timestamps = false;
    protected $guarded = [];
}
