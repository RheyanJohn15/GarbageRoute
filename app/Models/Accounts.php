<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    protected $primaryKey = 'acc_id';
    protected $fillable = [
        'acc_type',
        'acc_name',
        'acc_username',
        'acc_password',
        'acc_status',
        'acc_token'
    ];
}
