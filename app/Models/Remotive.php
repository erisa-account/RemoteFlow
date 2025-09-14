<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remotive extends Model
{
    use HasFactory;

    protected $table = 'remotive'; // your table name

    protected $fillable = [
        'user_id',
        'status_id',
        'date',
    ];
}

