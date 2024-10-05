<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrgyList extends Model
{
    use HasFactory;
    protected $table = 'brgy_lists';
    protected $primaryKey = 'brgy_id';
    protected $fillable = [
        'zone_id',
        'brgy_name'
    ];
}
