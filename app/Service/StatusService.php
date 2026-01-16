<?php
namespace App\Service;

use App\Models\Status;
use App\Models\Remotive; 
use App\Models\LeaveRequest;
  
class StatusService
{
    public function getAll()
    {
        return Status::all(['id', 'status']);
    }
     
    public function getStatusesNotOnSite()
    {
        return Remotive::with(['user', 'status'])
            ->where('status_id', '!=', 1)
            ->get(['id', 'user_id', 'status_id', 'date']);
    }

    public function getApprovedLeaves()
    {
        return LeaveRequest::with(['user'])
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('is_replacement', 0)
              ->orWhereNull('is_replacement');
        })
        ->latest()
        ->get();
    }
    
} 

