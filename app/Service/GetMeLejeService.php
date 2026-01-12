<?php
namespace App\Service;

use App\Models\Status;
use App\Resources\MeLejeResource;
use App\Models\Remotive;
use App\Models\LeaveRequest;
use Carbon\Carbon;
  

class GetMeLejeService{

    public function getMeLeje()
    {
        
            return MeLejeResource::collection(
                LeaveRequest::with(['user', 'type'])
                    ->latest()
                    ->get()
            );
    }

    public function getFilteredLeaveRequestTable($userId, $status = null, $preset = null, $startDate = null, $endDate = null)
    {
        $query = LeaveRequest::query(); 

    // filter by user_id
    if (!empty($userId)) {
        $query->where('user_id', $userId);
    }

    // filter by status
        if (!empty($status)) {
            $query->where('status', $status);
        }

   if ($preset === 'custom') {
        if ($startDate && $endDate) {
            $query->where(function($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                  ->where('end_date', '>=', $startDate);
            });
        }
    } 
    // filter by preset (date)
    else if (!empty($preset)) {
        $now = Carbon::now();

         switch ($preset) {
            case 'yesterday':
                $start = $now->copy()->subDay()->toDateString();
                $end   = $start;
                break;

            case '7':
                $start = $now->copy()->subDays(6)->toDateString();
                $end   = $now->toDateString();
                break;

            case '30':
                $start = $now->copy()->subDays(29)->toDateString();
                $end   = $now->toDateString();
                break;

            case 'last_week':
                $start = $now->copy()->startOfWeek()->subWeek()->toDateString();
                $end   = $now->copy()->endOfWeek()->subWeek()->toDateString();
                break;

            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth()->toDateString();
                $end   = $now->copy()->subMonth()->endOfMonth()->toDateString();
                break;

            case 'last_year':
                $start = $now->copy()->subYear()->startOfYear()->toDateString();
                $end   = $now->copy()->subYear()->endOfYear()->toDateString();
                break;

            default:
                $start = $end = null;
        }

        if ($start && $end) {
            $query->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
        }
    }

    return $query
    ->with(['user', 'type'])
    ->orderBy('start_date', 'desc')
    ->orderBy('end_date', 'desc')
    ->get();

}

public function resolveDateRange($preset, $startDate = null, $endDate = null)
{
    $now = Carbon::now();

    if ($preset === 'custom') {
        return [
            Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay(),
        Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay()
        ];
    }

    switch ($preset) {
    case 'yesterday':
        return [
            $now->copy()->subDay()->startOfDay(),
            $now->copy()->subDay()->endOfDay()
        ];

    case '7':
        return [
            $now->copy()->subDays(6)->startOfDay(), // last 7 days including today
            $now->copy()->endOfDay()
        ];

    case '30':
        return [
            $now->copy()->subDays(29)->startOfDay(), // last 30 days
            $now->copy()->endOfDay()
        ];

    case 'last_week':
        return [
            $now->copy()->startOfWeek()->subWeek(),
            $now->copy()->endOfWeek()->subWeek()
        ];

    case 'last_month':
        return [
            $now->copy()->subMonth()->startOfMonth(),
            $now->copy()->subMonth()->endOfMonth()
        ];

    case 'last_year':
        return [
            $now->copy()->subYear()->startOfYear(),
            $now->copy()->subYear()->endOfYear()
        ];

    default:
        // fallback: current month
        return [
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth()
        ];
}
}
} 