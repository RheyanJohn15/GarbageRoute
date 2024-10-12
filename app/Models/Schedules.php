<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $primaryKey = 'sched_id';
    protected $fillable = [
        'td_id',
        'days',
        'collection_start',
        'collection_end'
    ];
}
