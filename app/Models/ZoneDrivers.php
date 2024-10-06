<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneDrivers extends Model
{
    use HasFactory;
    protected $table = 'zone_drivers';
    protected $primaryKey = 'zd_id';
    protected $fillable = [
        'zone_id',
        'td_id',
        'type'
    ];
}
