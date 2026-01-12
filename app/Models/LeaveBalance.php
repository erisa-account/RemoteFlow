<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    
    protected $table = 'leave_balances';

    // Columns you want to allow mass assignment
    protected $fillable = [
        'user_id',              
        'year',
        'total_days',
        'used_days',  
        'starting_date',
                 
    ];

    protected $casts = [
        'starting_date' => 'date',
    ];

   // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationships
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type_id');
    }
}
