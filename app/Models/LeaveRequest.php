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
        'uses_comp_time',
        'status',
        'medical_certificate_path',
        'approved_at',
        'approver_id',
        'rejected_at',
        'rejection_reason',
        'requested_at',
    ];

    // Cast dates automatically to Carbon objects
    protected $dates = [
        'start_date',
        'end_date',
        'approved_at',
        'created_at',
        'updated_at',
        'rejected_at',
        'requested_at',
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

}