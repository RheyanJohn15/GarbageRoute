<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoDataCoordinates extends Model
{
    use HasFactory;
    protected $table = 'geo_data_coordinates';
    protected $primaryKey = 'gdc_id';
    protected $fillable = [
        'gd_id',
        'gd_longitude',
        'gd_latitude'
    ];
}
