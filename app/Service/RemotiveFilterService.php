<?php
namespace App\Service;

use App\Models\User;
use App\Models\Remotive;
use Carbon\Carbon;

class RemotiveFilterService
{
    public function getUserName(){ 
    return User::all();
    //return User::select('id', 'name')->get();
    }

    public function getRemotiveTable()
    {
     return Remotive::with(['user', 'status'])
            ->select('id', 'user_id', 'status_id', 'date', 'created_at', 'updated_at')
            ->get();
    } 
    
   public function getFilteredRemotiveTable($userId, $statusId, $preset, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();
        $debugNow = $now->toDateTimeString();

        \Log::info('Excel filter debug START', [
            'preset' => $preset,
            'now' => $now->toDateTimeString(),
        ]);
        $query = Remotive::query(); 

    // filter by user_id
    if (!empty($userId)) {
        $query->where('user_id', $userId);
    }

    // filter by status_id
    if (!empty($statusId)) {
        $query->where('status_id', $statusId);
    }

     if ($preset === 'custom') {
    if ($startDate && $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
    } 
    elseif ($startDate) {
        $query->whereDate('date', '>=', $startDate);
    } elseif ($endDate) {
        $query->whereDate('date', '<=', $endDate);
    }
    }
    
    
else if (!empty($preset)) {
        $now = Carbon::now();

        switch ($preset) {
            case 'yesterday':
                $query->whereDate('date', $now->subDay()->toDateString());
                break;

               

            case '7':
               $query->whereBetween('date', [
               $now->copy()->subDays(7)->toDateString(),
               $now->copy()->toDateString()
                ]);
                break;

  
            case '30':
    $query->whereBetween('date', [
        $now->copy()->subDays(30)->toDateString(),
        $now->copy()->toDateString()
    ]);
    break;

            case 'last_week':
                $query->whereBetween('date', [
                    $now->copy()->startOfWeek()->subWeek(),
                    $now->copy()->endOfWeek()->subWeek()
                ]);
                break;

            case 'last_month':
                $query->whereMonth('date', $now->copy()->subMonth()->month);
                break;

            case 'last_year':
                $query->whereYear('date', $now->copy()->subYear()->year);
                break;
        }
    }

    // return the results
    //return $query->get(); 
    

    \Log::info('Excel filter debug SQL', [
    'sql' => $query->toSql(),
    'bindings' => $query->getBindings()
]);
return $query->with(['user', 'status'])->get();
}

public function resolveDateRange($preset, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();

        if ($preset === 'custom') {
            return [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ];
        }

        switch ($preset) {
            case 'yesterday':
                return [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()];

            case '7':
                return [$now->copy()->subDays(7)->startOfDay(), $now->copy()->endOfDay()];

            case '30':
                return [$now->copy()->subDays(30)->startOfDay(), $now->copy()->endOfDay()];

            case 'last_week':
                return [$now->copy()->startOfWeek()->subWeek()->startOfDay(), $now->copy()->endOfWeek()->subWeek()->endOfDay()];

            case 'last_month':
                return [$now->copy()->subMonth()->startOfMonth()->startOfDay(), $now->copy()->subMonth()->endOfMonth()->endOfDay()];

            case 'last_year':
                return [$now->copy()->subYear()->startOfYear()->startOfDay(), $now->copy()->subYear()->endOfYear()->endOfDay()];

            default:
                // current month
                return [$now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfMonth()->endOfDay()];
        }
    }

    
}
