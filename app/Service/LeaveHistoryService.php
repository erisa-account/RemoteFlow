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
             ->orderByRaw("
        CASE
            WHEN status = 'pending' THEN 1
            WHEN status = 'approved' THEN 2
            WHEN status = 'rejected' THEN 3
            ELSE 4
        END
    ")
    ->orderBy('id', 'desc')
            ->get(); 
    }

    public function getByUser($userId)
    {
        return LeaveRequest::with(['user', 'type'])
            ->where('user_id', $userId)
             ->orderByRaw("
        CASE
            WHEN status = 'pending' THEN 1
            WHEN status = 'approved' THEN 2
            WHEN status = 'rejected' THEN 3
            ELSE 4
        END
    ")
    ->orderBy('id', 'desc')
            ->get();
    }
}