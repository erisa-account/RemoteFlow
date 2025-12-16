<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    
    protected $table = 'leave_requests';

    
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days',
        'reason',
        'is_replacement',
        'status',
        'medical_certificate_path',
        'approved_at',
        'approver_id',
        'rejected_at',
        'rejection_reason',
        'requested_at',
    ];

    // Cast dates automatically to Carbon objects
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'=> 'datetime',
        'approved_at'=> 'datetime',
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
        'rejected_at'=> 'datetime',
        'requested_at'=> 'datetime',
        'is_replacement'=> 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    } 

    public function approver()
    { 
        return $this->belongsTo(User::class, 'approver_id');
    }

} 