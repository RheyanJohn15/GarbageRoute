<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DumpsiteTurnovers extends Model
{
    use HasFactory;
    protected $table = 'dumpsite_turnovers';
    protected $primaryKey = 'dst_id';
    protected $fillable = [
        'td_id',
        'dt_id',
        'percentage'
    ];
}
