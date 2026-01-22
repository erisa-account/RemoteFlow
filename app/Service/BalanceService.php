<?php
namespace App\Service;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    public function getOrCreate(int $userId): LeaveBalance
    {
        return LeaveBalance::firstOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'starting_date'     => null,
                'year'              => null,
                'total_days'        => 0,
                'used_days'         => 0,
                'carried_over_days' => 0,
            ]
        );
    } 
    
    


        public function storeStartingDate(int $userId, string $starting_date): LeaveBalance
        {
            $balance = $this->getOrCreate($userId);

            $balance->starting_date = Carbon::parse($starting_date)->toDateString();

            /*if ($balance->wasRecentlyCreated) {
                $balance->used_days = 0;
                $balance->carried_over_days = 0;
                $balance->total_days = 0; // will be calculated later
            }*/
            $balance->save();
            return $balance;
        }



        public function updateCarriedOverDays(int $userId): LeaveBalance
        {
            $balance = $this->getOrCreate($userId);
             $today = Carbon::today();

            if (!$balance->starting_date) {
                return $balance;
            }

            $start = Carbon::parse($balance->starting_date);

            // If starting date is in the future → no leave
            if ($start->greaterThan($today)) {
                $balance->total_days = 0;
                $balance->save();
                return $balance;
            }

            $yearsWorked = $start->diffInYears($today);

            // Calculate base total days
            if ($yearsWorked >= 1) {
                $totalDays = 22; // old employee
            } else {
                $monthsWorked = $start->diffInMonths($today);
                $totalDays = ceil($monthsWorked * 1.83); // new employee
            }
            if ($today->month === 1 && $today->day === 1) {
        $totalDays += 22;
    }

            // Calculate carried over dynamically (never store in DB)
            $carriedOver = 0;
            if ($today->month === 1 && $today->day === 1) {
                $carriedOver = max($balance->total_days - $balance->used_days, 0);
            }

            // Expire carried over after 31/03
            if ($today->greaterThan(Carbon::create($today->year, 3, 31))) {
                $carriedOver = 0;
            }

            // Total days for current year = base days + carried over
            $balance->total_days = $totalDays; // store only the entitlement for this year
            $balance->save();

            // **Return balance with carried over dynamically added**
            // Use a dynamic property to return for frontend or summary
            $balance->carried_over_days = $carriedOver;
            $balance->remaining_days = max($totalDays + $carriedOver - $balance->used_days, 0);

            return $balance;
        }



        public function hasEnoughLeave(LeaveBalance $balance, int $requestedDays): bool
        {
            $available = max($balance->total_days - $balance->used_days, 0);
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
            
            //$days = $request->days;

            if (!$r->type->is_paid || $r->is_replacement) return;

            $balance = $this->getOrCreate($r->user_id);

            //$this->updateCarriedOverDays($r->user_id);

            // Check if enough leave
            if (!$this->hasEnoughLeave($balance, $r->days)) {
                throw new \Exception("You don't have enough leave days for this request.");
            }

            $this->useLeave($balance, $r->days);      
            
        }


        public function revertApproval(LeaveRequest $r): void
        {
            if (!$r->type->is_paid || $r->is_replacement) return;

            $balance = $this->getOrCreate($r->user_id);

            $this->revertLeave($balance, $r->days);
        }
        
        

        public function getLeaveSummary(int $userId): array
            {
                $this->updateCarriedOverDays($userId);
                $balance = $this->getOrCreate($userId);
                   

                /*$carriedOver = $balance->carried_over_days ?? 0;*/

                // Remaining days = total_days - used_days
                $remaining = max($balance->total_days - $balance->used_days, 0);

                return [
                    'total_days'        => $balance->total_days,
                    'used_days'         => $balance->used_days,
                    'remaining_days'    => $remaining,
                    'carried_over_days' => $balance->carried_over_days,
                ];
            }          


        /*public function debugCarriedOver(
            int $userId,
            int $totalDays = 20,         // simulate total leave
            int $usedDays = 0,           // simulate used leave
            int $carriedOverDays = 0,    // simulate existing carried over
            string $date = null          // optional date for calculation, default today
            )
        {
            $balance = $this->getOrCreate($userId);

            // Inject test values
            $balance->total_days = $totalDays;
            $balance->used_days = $usedDays;
            $balance->carried_over_days = $carriedOverDays;

            $today = $date ? Carbon::parse($date) : Carbon::today();

            // Step 1: Calculate remaining days
            $remainingDays = max($balance->total_days - $balance->used_days, 0);

            // Step 2: Simulate carried over for Jan 1
            $carriedOverNextYear = 0;
            if ($today->month === 1 && $today->day === 1) {
                $carriedOverNextYear = $remainingDays;
            }

            // Step 3: Include expiry check (31/03)
            $expireCarriedOver = ($today->month === 3 && $today->day === 31);

            return [
                'today'                     => $today->toDateString(),
                'total_days'                => $balance->total_days,
                'used_days'                 => $balance->used_days,
                'remaining_days'            => $remainingDays,
                'carried_over_days'         => $balance->carried_over_days,
                'carried_over_next_year'    => $carriedOverNextYear,
                'carried_over_will_expire'  => $expireCarriedOver,
            ];
        }*/
}