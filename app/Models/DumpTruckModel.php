<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DumpTruckModel extends Model
{
    use HasFactory;

    protected $table = 'dump_truck';
    protected $primaryKey = 'dt_id';
    protected $fillable = [
       'model',
       'can_carry',
       'td_id',
       'profile_pic'
    ];
}
