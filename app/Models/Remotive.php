<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remotive extends Model{
    use HasFactory;

    protected $table = 'remotive'; 

    protected $fillable = [
        'user_id',
        'status_id',
        'date',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

}

