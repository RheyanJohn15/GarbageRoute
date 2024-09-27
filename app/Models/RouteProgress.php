<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteProgress extends Model
{
    use HasFactory;
    protected $table = 'route_progress';
    protected $primaryKey = 'rp_id';
    protected $fillable = [
        'r_id',
        'rp_progress',
        'rp_status',
    ];
}
