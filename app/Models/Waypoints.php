<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waypoints extends Model
{
    use HasFactory;
    protected $table = 'waypoints';
    protected $primaryKey = 'brgy_id';
    protected $fillable = [
        'brgy_id',
        'longitude',
        'latitude',
        'zone_id'
    ];
}
