<?php
namespace App\Service; 

use App\Models\LeaveRequest;

class OverLapValidator
{
public function hasOverlap(int $userId, string $start, string $end): bool
{
    return LeaveRequest::query()
        ->where('user_id', $userId)
        ->whereIn('status', ['pending','approved'])
        ->where('leave_type_id', '!=', 4)
        ->where(function ($q) use ($start, $end) {
        $q->whereBetween('start_date', [$start,$end])
        ->orWhereBetween('end_date', [$start,$end])
        ->orWhere(function ($q) use ($start, $end) {
        $q->where('start_date','<=',$start)->where('end_date','>=',$end);
          });
        })->exists();
        }

        public function hasOverlapReplacment(int $userId, string $start, string $end) : bool {
          return LeaveRequest::query()
          ->where('user_id', $userId)
          ->where('status', ['pending', 'approved'])
          ->where('leave_type_id', '!=', 4)
          ->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start,$end])
            ->orWhere(function ($q) use ($start, $end) {
              $q->where('start_date', '<=', $start)->where('end_date', '>=', $end);
            });
          })->exists(); 
        }
} 