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

            if ($previous && !$balance->carried_over_expired) {
                $previousTotal = $this->calculateEntitlement($previous);
                $carriedOver = max($previousTotal + $previous->carried_over_days - $previous->used_days, 0);
            }

            $balance->carried_over_days = $carriedOver;
            $balance->save();

            Log::info('Calculated carried over days', [
                'user_id' => $userId,
                'year' => $year,
                'carried_over_days' => $carriedOver,
            ]);
        }
        Log::info("Before useLeave", [
    'year' => $year,
    'carried' => $balance->carried_over_days,
    'used' => $balance->used_days
]);

        return $balance;
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
        if ($balance->carried_over_days >= $days) {
            $balance->carried_over_days -= $days;
        } else {
            $remaining = $days - $balance->carried_over_days;
            $balance->carried_over_days = 0;
            $balance->used_days += $remaining;
        }
        $balance->save();


Log::info('useLeave called', [
    'year' => $balance->year,
    'days' => $days,
    'carried_before' => $balance->carried_over_days,
    'used_before' => $balance->used_days,
]);
    }

    // Revert leave
    public function revertLeave(LeaveBalance $balance, int $days)
    {
        $usedFromTotal = max($days - ($balance->carried_over_days ?? 0), 0);
        $balance->used_days = max($balance->used_days - $usedFromTotal, 0);
        $balance->carried_over_days += ($days - $usedFromTotal);
        $balance->save();
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
            $daysInYear = min($end, $yearEnd)->diffInDays($current) + 1;

            if (!$this->hasEnoughLeave($balance, $daysInYear)) {
                throw new \Exception("Not enough leave days for year $year.");
            }

            $this->useLeave($balance, $daysInYear);

            $current = Carbon::create($year + 1, 1, 1); // move to next year
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

        $current = $start->copy();
        while ($current->lte($end)) {
            $year = $current->year;
            $balance = $this->getOrCreate($r->user_id, $year);

            $yearEnd = Carbon::create($year, 12, 31);
            $daysInYear = min($end, $yearEnd)->diffInDays($current) + 1;

            $this->revertLeave($balance, $daysInYear);

            $current = Carbon::create($year + 1, 1, 1); // move to next year
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