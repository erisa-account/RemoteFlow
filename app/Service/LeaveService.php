<?php

namespace App\Service;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;

class LeaveService {

    public function getLeaves(array $filters = [])
    {
         return LeaveRequest::with(['type', 'user']) // or user, depending on your relation
        ->when(!empty($filters['status']), fn($q) => $q->where('status', $filters['status']))
        ->when(!empty($filters['user']), fn($q) => $q->where('user_id', $filters['user']))
        ->when(!empty($filters['starting_date']), fn($q) => $q->where('start_date', '>=', $filters['starting_date']))
        // Filter by ending date (leaves ending on or before this date)
        ->when(!empty($filters['ending_date']), fn($q) => $q->where('end_date', '<=', $filters['ending_date']))
        ->orderByRaw("
            CASE
                WHEN status = 'pending' THEN 1
                WHEN status = 'approved' THEN 2
                WHEN status = 'rejected' THEN 3
                ELSE 4
            END
        ")
        ->orderBy('start_date', 'desc')
        ->get();
    }
}