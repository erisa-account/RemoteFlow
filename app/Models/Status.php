<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status'; // your table name

    protected $fillable = [
        'status',
    ]; 

    public function remotive()
    {
        return $this->hasMany(Remotive::class);
    }
}

