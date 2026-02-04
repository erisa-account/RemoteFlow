<?php
namespace App\Service;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class BalanceService
{
    private const ANNUAL_DAYS = 22;
    private const DAYS_PER_MONTH = 1.83;


    public function getOrCreate(int $userId, int $year, bool $createIfMissing = true): ? LeaveBalance
    {
        $query = LeaveBalance::where('user_id', $userId)
        ->where('year', $year);

    //$balance = $query->first();
    $balance = $query->with('user')->first();


    if (!$balance && $createIfMissing) {
        $balance = LeaveBalance::create([
            'user_id' => $userId,
            'year' => $year,
            'used_days' => 0,
            'carried_over_days' => 0,
        ]);
        $balance->load('user');
    }

    return $balance;
    }


    

        public function storeStartingDate(int $userId, string $starting_date): LeaveBalance
        {
            $user = User::findOrFail($userId);
            $user->starting_date = Carbon::parse($starting_date)->toDateString();
            $user->save();

            $year = Carbon::parse($starting_date)->year;
            //dump("User starting date:", $user->starting_date, "Year:", $year);
            \Log::info('User starting date: '.$user->starting_date.' Year: '.$year);

            $balance = $this->getOrCreate($userId, $year);
            /*//dump("Balance after getOrCreate:", $balance->toArray());
            \Log::info('Balance after getOrCreate: '.json_encode($balance->toArray()));

            $this->calculateEntitlement($balance);
            //dump("Balance after calculateEntitlement:", $balance->toArray());
            \Log::info('Balance after calculateEntitlement: '.json_encode($balance->toArray()));
            $balance->save();*/

            return $balance;
        }




        public function calculateEntitlement(LeaveBalance $balance): array
    {
        $startingDate = optional($balance->user)->starting_date;  
    return [
        'startingDate' => $startingDate,
        'annualDays' => self::ANNUAL_DAYS,
        'daysPerMonth' => self::DAYS_PER_MONTH,
    ];
    }



        /*public function hasEnoughLeave(LeaveBalance $balance, int $requestedDays): bool
        {
            $available = max($balance->total_days + $balance->carried_over_days - $balance->used_days,0);
            return $available >= $requestedDays;
        }*/

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

            /*if (!$this->hasEnoughLeave($balance, $r->days)) {
                throw new \Exception('Not enough leave days.');
            }*/

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

    // Current year leave balance
    $balance = $this->getOrCreate($userId, $year);

    // Previous year for carried over
    $prev = LeaveBalance::where('user_id', $userId)
        ->where('year', $year - 1)
        ->first();

    $carried = 0;
if ($prev) {
    // Dynamically calculate total days for prev year
    $prevData = [
        'startingDate' => optional($prev->user)->starting_date,
        'annualDays' => 22,
        'daysPerMonth' => 1.83,
        'usedDays' => $prev->used_days
    ];

    // JS formula replicated in PHP
    $totalPrevDays = 0;
    if ($prevData['startingDate']) {
        $start = Carbon::parse($prevData['startingDate']);
        if ($start->year === $year - 1) {
            $monthsWorked = max(12 - $start->month + 1, 0); // July → Dec = 6 months
            $totalPrevDays = ceil($monthsWorked * $prevData['daysPerMonth']);
        } else {
            $totalPrevDays = $prevData['annualDays'];
        }
    } else {
        $totalPrevDays = $prevData['annualDays'];
    }

    $carried = max($totalPrevDays - $prevData['usedDays'], 0);
}          



return [
    'year' => $year,
    'startingDate' => $balance?->user?->starting_date,
    'usedDays' => $balance->used_days,
    'carriedOverDays' => $carried, // dynamically calculated
    'annualDays' => 22,
    'daysPerMonth' => 1.83,
];
}
        
}