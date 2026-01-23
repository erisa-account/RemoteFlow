<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\BalanceService;
use App\Models\LeaveBalance;


class ProcessYearRollover extends Command
{
    protected $signature = 'leave:year-rollover';

    public function handle(BalanceService $service)
    {
        $year = now()->year;
        $prevYear = $year - 1;

        LeaveBalance::where('year', $prevYear)->each(function ($prev) use ($service, $year) {
            $current = $service->getOrCreate($prev->user_id, $year);

            $unused = max(
                $prev->total_days + $prev->carried_over_days - $prev->used_days,
                0
            );

            $current->carried_over_days = $unused;
            $service->calculateEntitlement($current);
            $current->save();
        });
    }
}