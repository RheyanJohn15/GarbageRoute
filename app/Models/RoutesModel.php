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
      'r_start_longitude',
      'r_start_latitude',
      'r_end_longitude',
      'r_end_latitude',
      'r_assigned_truck'
    ];
}
