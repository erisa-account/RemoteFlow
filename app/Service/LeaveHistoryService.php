<?php
namespace App\Service;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;

class LeaveHistoryService
{
    public function getAll($userId)
    {
        return LeaveRequest::with(['user', 'type'])
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get(); 
    }

    public function getByUser($userId)
    {
        return LeaveRequest::with(['user', 'type'])
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();
    }
}