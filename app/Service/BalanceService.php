<?php
namespace App\Service;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BalanceService
{
    private const ANNUAL_DAYS = 22;
    private const DAYS_PER_MONTH = 1.83;

    // Get or create leave balance for a user and year
    public function getOrCreate(int $userId, int $year, bool $createIfMissing = true): ?LeaveBalance
    {
        $balance = LeaveBalance::firstOrCreate(
        ['user_id' => $userId, 'year' => $year],
        ['used_days' => 0, 'carried_over_days' => 0, 'total_days' => 0]
    );

    $balance->load('user');

    // Only calculate carried-over if it is 0 (first initialization)
    if ($balance->carried_over_days === 0) {

    $previous = LeaveBalance::where('user_id', $userId)
        ->where('year', $year - 1)
        ->first();

    $carriedOver = 0;
    $previousTotal = 0;

    if ($previous && !$balance->carried_over_expired) {
        $previousTotal = $this->calculateEntitlement($previous);
        $carriedOver = max($previousTotal + $previous->carried_over_days - $previous->used_days, 0);
    }

    // SAFE debug: write to log instead of dd()
    Log::info('getOrCreate debug', [
        'current_year_balance' => $balance->toArray(),
        'previous_year_balance' => $previous ? $previous->toArray() : null,
        'previous_entitlement' => $previousTotal,
        'calculated_carried_over' => $carriedOver,
    ]);

    $balance->carried_over_days = $carriedOver;
    $balance->save();
    }
    return $balance;
    }

    public function syncNextYearCarry(int $year, int $userId)
{
    
    $current = $this->getOrCreate($userId, $year);

    $entitlement = $this->calculateEntitlement($current);
    $remainingFromCurrent = max($entitlement - $current->used_days, 0);

    
    $next = LeaveBalance::firstOrNew([
        'user_id' => $userId,
        'year' => $year + 1
    ]);

    $beforeNext = [
        'carried_over_days' => $next->carried_over_days ?? null
    ];


    $next->carried_over_days = $remainingFromCurrent;
    $next->save();

    Log::info('SYNC NEXT YEAR CARRY', [
        'from_year' => $year,
        'to_year' => $year + 1,
        'entitlement_current_year' => $entitlement,
        'used_current_year' => $current->used_days,
        'remaining_calculated' => $remainingFromCurrent,
        'next_before' => $beforeNext,
        'next_after' => [
            'carried_over_days' => $next->carried_over_days
        ]
    ]);
}


    // Store or update user's starting date
    public function storeStartingDate(int $userId, string $startingDate): LeaveBalance
    {
        $user = User::findOrFail($userId);
        $user->starting_date = Carbon::parse($startingDate)->toDateString();
        $user->save();

        $year = Carbon::parse($startingDate)->year;
        return $this->getOrCreate($userId, $year);
    }

    // Calculate total leave entitlement dynamically
    public function calculateEntitlement(LeaveBalance $balance): int
    {
        $user = $balance->user;
        if (!$user || !$user->starting_date) return 0;

        $start = Carbon::parse($user->starting_date);
        $year = $balance->year;
        $now = now();

        if ($start->year < $year) return self::ANNUAL_DAYS;
        if ($start->year > $year) return 0;

        $endMonth = ($year === $now->year) ? $now->month : 12;
        $monthsWorked = max($endMonth - $start->month, 0);

        return (int) floor($monthsWorked * self::DAYS_PER_MONTH);
    }

    // Use leave (prioritize carried-over)
    public function useLeave(LeaveBalance $balance, int $days)
    {
        $before = [
        'used_days' => $balance->used_days,
        'carried_over_days' => $balance->carried_over_days,
    ];

        if ($balance->carried_over_days >= $days) {
            $balance->carried_over_days -= $days;
        } else {
            $remaining = $days - $balance->carried_over_days;
            $balance->carried_over_days = 0;
            $balance->used_days += $remaining;
        }
        $balance->save();


Log::info('USE LEAVE', [
        'year' => $balance->year,
        'days_requested' => $days,
        'before' => $before,
        'after' => [
            'used_days' => $balance->used_days,
            'carried_over_days' => $balance->carried_over_days,
        ]
    ]);
    }

    // Revert leave
    public function revertLeave(LeaveBalance $balance, int $days)
    {
        $before = [
        'used_days' => $balance->used_days,
        'carried_over_days' => $balance->carried_over_days,
    ];

        $usedFromTotal = max($days - ($balance->carried_over_days ?? 0), 0);
        $balance->used_days = max($balance->used_days - $usedFromTotal, 0);
        $balance->carried_over_days += ($days - $usedFromTotal);
        $balance->save();

         Log::info('REVERT LEAVE', [
        'year' => $balance->year,
        'days_reverted' => $days,
        'usedFromTotal_calculated' => $usedFromTotal,
        'before' => $before,
        'after' => [
            'used_days' => $balance->used_days,
            'carried_over_days' => $balance->carried_over_days,
        ]
    ]);


    }

    // Apply approved leave (updated for cross-year support)
    public function applyApproval(LeaveRequest $r): void
    {
        if (!$r->type->is_paid || $r->is_replacement) return;

        $start = Carbon::parse($r->start_date);
        $end = Carbon::parse($r->end_date);

        $current = $start->copy();
        while ($current->lte($end)) {
            $year = $current->year;
            $balance = $this->getOrCreate($r->user_id, $year);

            // Calculate number of leave days in this year
            $yearEnd = Carbon::create($year, 12, 31);
            
            $segmentEnd = min($end, $yearEnd);

            $daysInYear = app(LeaveCalculator::class)->businessDays(
                $current->toDateString(),
                $segmentEnd->toDateString()
            );
            

            if (!$this->hasEnoughLeave($balance, $daysInYear)) {
                throw new \Exception("Not enough leave days for year $year.");
            }

            $this->useLeave($balance, $daysInYear);

            if ($segmentEnd->lt($end)) {
                $this->syncNextYearCarry($year, $r->user_id);
            }

            $current = $segmentEnd->copy()->addDay();

            //$current = Carbon::create($year + 1, 1, 1); // move to next year
        }

        

        Log::info('Split debug', [
    'year' => $year,
    'daysInYear' => $daysInYear,
]);
    }

    // Revert approved leave (updated for cross-year support)
    public function revertApproval(LeaveRequest $r): void
    {
        if (!$r->type->is_paid || $r->is_replacement) return;

        $start = Carbon::parse($r->start_date);
        $end = Carbon::parse($r->end_date);
         $affectedYears = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $year = $current->year;
            $affectedYears[$year] = true;
            $balance = $this->getOrCreate($r->user_id, $year);

            $yearEnd = Carbon::create($year, 12, 31);
        $segmentEnd = min($end, $yearEnd); // same logic as applyApproval

        

        // Calculate business days (excluding weekends) for this year
        $daysInYear = app(LeaveCalculator::class)->businessDays(
            $current->toDateString(),
            $segmentEnd->toDateString()
        );

         Log::info('REVERT LOOP SEGMENT', [
        'processing_year' => $year,
        'segment_start' => $current->toDateString(),
        'segment_end' => $segmentEnd->toDateString(),
        'daysInYear' => $daysInYear,
        'balance_before' => [
            'used_days' => $balance->used_days,
            'carried_over_days' => $balance->carried_over_days,
        ]
    ]);
                $this->revertLeave($balance, $daysInYear);
                 $current = $segmentEnd->copy()->addDay();
    }
            
            /*if ($segmentEnd->lt($end)) {
                $this->syncNextYearCarry($year, $r->user_id);
            }

           // $this->syncNextYearCarry($year, $r->user_id);

            $current = $segmentEnd->copy()->addDay();*/

            //$current = Carbon::create($year + 1, 1, 1); // move to next year

            // 🔥 AFTER everything is reverted, now sync carry properly
   $startYear = Carbon::parse($r->start_date)->year;
$endYear   = Carbon::parse($r->end_date)->year;

if ($endYear > $startYear) {
    $this->syncNextYearCarry($startYear, $r->user_id);
}


        
    }

    // Check if enough leave is available
    public function hasEnoughLeave(LeaveBalance $balance, int $requestedDays): bool
    {
        $available = max(
            $this->calculateEntitlement($balance) // dynamically calculate total leave
            + $balance->carried_over_days       // add any remaining carried-over
            - $balance->used_days,              // subtract used days
            0
        );

        return $available >= $requestedDays;
    }

    // Get summary for JS/UI
    public function getLeaveSummary(int $userId): array
    {
        $year = now()->year;
        $balance = $this->getOrCreate($userId, $year);
        $balance->load('user');

        $totalDays = $this->calculateEntitlement($balance); // dynamic
        $remaining = max($totalDays + $balance->carried_over_days - $balance->used_days, 0);

        return [
            'year' => $year,
            'total_days' => $totalDays,
            'used_days' => $balance->used_days,
            'carried_over_days' => $balance->carried_over_days,
            'remaining_days' => $remaining,
        ];
    }
}