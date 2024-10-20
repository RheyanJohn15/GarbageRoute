<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneSubSched extends Model
{
    use HasFactory;
    protected $table = 'zone_sub_scheds';
    protected $primaryKey = 'zss_id';
    protected $fillable = [
        'zone_id',
        'wp_id',
        'days'
    ];
}
