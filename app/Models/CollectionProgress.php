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
        'wp_id',
        'status',
    ];
}
