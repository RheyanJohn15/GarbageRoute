<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zones extends Model
{
    use HasFactory;
    protected $table = 'zones';
    protected $primaryKey = 'zone_id';
    protected $fillable = [
        'zone_name',
        'zone_color'
    ];
}
