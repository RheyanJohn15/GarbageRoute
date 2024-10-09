<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionProgress extends Model
{
    use HasFactory;
    protected $table = 'collection_progress';
    protected $primaryKey = 'cp_id';
    protected $fillable = [
        'td_id',
        'brgy_id',
        'status',
        'time_entered',
        'time_out'
    ];
}
