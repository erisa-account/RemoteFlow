<?php
namespace App\Service;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    private const ANNUAL_DAYS = 22;
    private const DAYS_PER_MONTH = 1.83;


    public function getOrCreate(int $userId, int $year, bool $createIfMissing = true): ? LeaveBalance
    {
        $query = LeaveBalance::where('user_id', $userId)
        ->where('year', $year);

    $balance = $query->first();

    if (!$balance && $createIfMissing) {
        $balance = LeaveBalance::create([
            'user_id' => $userId,
            'year' => $year,
            'starting_date' => null,
            'total_days' => 0,
            'used_days' => 0,
            'carried_over_days' => 0,
        ]);
    }

    return $balance;
    }


    /*public function getOrCreate(int $userId, int $year): LeaveBalance
    {
        return LeaveBalance::firstOrCreate(
            [
                'user_id' => $userId,
                'year'    => $year,
            ],
            [
                'starting_date'     => null,
                'total_days'        => 0,
                'used_days'         => 0,
                'carried_over_days' => 0,
            ]
        );
    }*/

        public function storeStartingDate(int $userId, string $starting_date): LeaveBalance
        {
            $year = Carbon::parse($starting_date)->year;
        $balance = $this->getOrCreate($userId, $year);

        $balance->starting_date = Carbon::parse($starting_date)->toDateString();
        $this->calculateEntitlement($balance);
        $balance->save();

        return $balance;
        }




        public function calculateEntitlement(LeaveBalance $balance): void
    {
        if (!$balance->starting_date) {
            return;
        }

        $start = Carbon::parse($balance->starting_date);

        if ($start->year === $balance->year) {
            // First year – prorated
            $months = max(
                $start->copy()->startOfMonth()->diffInMonths(
                    Carbon::create($balance->year, 12, 31)
                ) + 1,
                0
            );
            $balance->total_days = (int) ceil($months * self::DAYS_PER_MONTH);
        } else {
            $balance->total_days = self::ANNUAL_DAYS;
        }
    }



        public function hasEnoughLeave(LeaveBalance $balance, int $requestedDays): bool
        {
            $available = max($balance->total_days + $balance->carried_over_days - $balance->used_days,0);
            return $available >= $requestedDays;
        }

        public function useLeave(LeaveBalance $balance, int $days)
            {
                // Use carried over days first
                if ($balance->carried_over_days >= $days) {
                    $balance->carried_over_days -= $days;
                } else {
                    $remaining = $days - $balance->carried_over_days;
                    $balance->carried_over_days = 0;
                    $balance->used_days += $remaining;
                }

                $balance->save();
            }

   
        public function revertLeave(LeaveBalance $balance, int $days)
            {
                // Return leave to carried over first if possible
                $usedFromTotal = max($days - ($balance->carried_over_days ?? 0), 0);

                $balance->used_days = max($balance->used_days - $usedFromTotal, 0);
                $balance->carried_over_days += ($days - $usedFromTotal);

                $balance->save();
            }



        public function applyApproval(LeaveRequest $r): void
        {
           
            if (!$r->type->is_paid || $r->is_replacement) {
            return;
        }

            if (!$r->start_date) {
               throw new \Exception('Leave request has no start date.');
            }

            $year = Carbon::parse($r->start_date)->year;
            $balance = $this->getOrCreate($r->user_id, $year);

            if (!$this->hasEnoughLeave($balance, $r->days)) {
                throw new \Exception('Not enough leave days.');
            }

            $this->useLeave($balance, $r->days);
            }
    
           
        


        public function revertApproval(LeaveRequest $r): void
        {
            if (!$r->type->is_paid || $r->is_replacement) return;

            $year = Carbon::parse($r->start_date)->year;
            $balance = $this->getOrCreate($r->user_id, $year);

            $this->revertLeave($balance, $r->days);
        }
       
       

        public function getLeaveSummary(int $userId): array
            {
                 $year = now()->year;
        $balance = $this->getOrCreate($userId, $year);

        $available =
            $balance->total_days +
            $balance->carried_over_days -
            $balance->used_days;

        return [
            'year'              => $year,
            'total_days'        => $balance->total_days,
            'used_days'         => $balance->used_days,
            'carried_over_days' => $balance->carried_over_days,
            'remaining_days'    => max($available, 0),
        ];
            }          


        
}