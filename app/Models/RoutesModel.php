<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutesModel extends Model
{
    use HasFactory;
    protected $table = 'routes';
    protected $primaryKey = 'r_id';
    protected $fillable = [
      'r_name',
      'r_coordinates',
      'r_schedule',
      'r_status',
      'r_assigned_truck'
    ];
}
