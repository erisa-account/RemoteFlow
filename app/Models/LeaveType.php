<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    
    protected $table = 'leave_types';

    // Columns you want to allow mass assignment
    protected $fillable = [
        'key',               
        'display_name',      
        'is_paid',           
        'requires_document', 
        'color',             
    ];

    // Optional: cast boolean columns automatically
    protected $casts = [
        'is_paid' => 'boolean',
        'requires_document' => 'boolean',
    ];

    // Relationships
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type_id');
    }
}
