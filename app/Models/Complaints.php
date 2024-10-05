<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;

    protected $table = 'complaints';
    protected $primaryKey = 'comp_id';
    protected $fillable = [
        'comp_name',
        'comp_contact',
        'comp_email',
        'comp_nature',
        'comp_remarks',
        'comp_status',
        'comp_image'
    ];
}
