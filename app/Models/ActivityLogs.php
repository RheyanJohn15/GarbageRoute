<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    protected $primaryKey = 'act_id';
    protected $fillable = [
        'user_id',
        'action',
        'result',
        'route'
    ];
}
