<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayMarker extends Model
{
    protected $fillable = [
        'user_id', 'date', 'status', 'color',
        'note', 'leave_request_id'
    ];
}