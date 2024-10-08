<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveDrivers extends Model
{
    use HasFactory;
    protected $table = 'active_drivers';
    protected $primaryKey = 'ad_id';
    protected $fillable = [
        'td_id',
        'ad_coordinates',
        'status'
    ];
}
