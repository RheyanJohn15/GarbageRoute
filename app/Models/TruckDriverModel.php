<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckDriverModel extends Model
{
    use HasFactory;
    protected $table = 'truck_drivers';
    protected $primaryKey = 'td_id';
    protected $fillable = [
       'username',
       'password',
       'name',
       'license',
       'contact',
       'address'
    ];
}
