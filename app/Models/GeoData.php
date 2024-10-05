<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoData extends Model
{
    use HasFactory;
    protected $table = 'geo_data';
    protected $primaryKey = 'gd_id';
    protected $fillable = [
        'type',
        'geometry_type',
        'brgy_id',
        'property_color'
    ];
}
