<?php
namespace App\Service;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    public function getOrCreate(int $userId, int $year, int $defaultTotal = 20): LeaveBalance
    {
        return LeaveBalance::firstOrCreate(
         ['user_id' => $userId, 'year' => $year],
         ['total_days' => $defaultTotal]
        );
    } 


    public function storeStartingDate(int $userId, string $startingDate): LeaveBalance
        {
            $date = Carbon::parse($startingDate);
            $year = $date->year;

            // Calculate total days based on months from starting date to today
            $monthsDiff = $date->diffInMonths(Carbon::today());
            $totalDays = ceil($monthsDiff * 1.7);

            return LeaveBalance::updateOrCreate(
                ['user_id' => $userId],
                [
                    'starting_date' => $date,
                    'year'          => $year,
                    'total_days'    => $totalDays > 0 ? $totalDays : $defaultTotal,
                    'used_days'     => 0,
                    'forwarded_days'=> 0,
                ]
            );
        }
    
    
        
     public function calculateLeave(int $userId): ?array
    {
        $leaveBalance = LeaveBalance::where('user_id', $userId)->first();

        if (!$leaveBalance) {
            return null;
        }

        $today = Carbon::today();
        $startingDate = Carbon::parse($leaveBalance->starting_date);
        $usedDays = $leaveBalance->used_days ?? 0;

        // 1. Calculate total days based on months
        $monthsDiff = $startingDate->diffInMonths($today);
        $totalDays = ceil($monthsDiff * 1.7);

        // 2. Calculate forwarded days
        $forwardedDays = 0;
        if ($today->format('d/m') === '31/12') {
            $forwardedDays = max($totalDays - $usedDays, 0);
        }

        if ($today->format('d/m') === '31/03') {
            $forwardedDays = 0;
        }

        // 3. Determine how many leave days can be used
        // Priority: forwarded days first, then current total days
        $leaves = [
            'from_forwarded' => 0,
            'from_total'     => 0,
        ];

        if ($forwardedDays > 0) {
            $leaves['from_forwarded'] = $forwardedDays;
            $leaves['from_total'] = max($totalDays - $usedDays - $forwardedDays, 0);
        } else {
            $leaves['from_total'] = max($totalDays - $usedDays, 0);
        }

        return [
            'starting_date'   => $startingDate->toDateString(),
            'total_days'      => $totalDays,
            'used_days'       => $usedDays,
            'forwarded_days'  => $forwardedDays,
            'available_leave' => $leaves,
        ];
    }

                public function applyApproval(LeaveRequest $r): void
        {
            // Only count if paid type and NOT using comp time
            if (!$r->type->is_paid || $r->is_replacement) return;
          

            //$year = Carbon::parse($r->start_date)->year;
            $year = (int) Carbon::parse($r->start_date)->format('Y');
            $balance = $this->getOrCreate($r->user_id, $year);

            
            $balance->increment('used_days', $r->days);
               
            
            }


            public function revertApproval(LeaveRequest $r): void
        {
            if (!$r->type->is_paid || $r->is_replacement) return;

            $year = Carbon::parse($r->start_date)->year;
            $balance = $this->getOrCreate($r->user_id, $year);

            $days = $r->days;

            // Restore to current year first
            if ($balance->used_days >= $days) {
                $balance->used_days -= $days;
            } else {
                $remaining = $days - $balance->used_days;
                $balance->used_days = 0;
                $balance->forwarded_days += $remaining;
            }

            $balance->save();
        }

         public function getLeaveSummary(int $userId, int $year): array
            {

                 $balance = $this->getOrCreate($userId, $year);
                    //$this->refreshBalance($balance);

                    return [
                        'total_days'      => $balance->total_days,
                        'used_days'       => $balance->used_days,
                        'forwarded_days'  => $balance->forwarded_days,
                        'remaining_days'  =>
                            ($balance->total_days - $balance->used_days)
                            + $balance->forwarded_days,
                    ];
            } 


            


}