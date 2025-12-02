<?php
namespace App\Service;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class LeaveCalculator
{
    public function businessDays(string $start, string $end, array $holidayDates = [], bool $skipWeekends = true): int
    {
        $compensationDays = app(DayMarkerService::class)->getWeekendHolidays($holidayDates);
        $holidayDates = array_merge($holidayDates, $compensationDays);

        $period = CarbonPeriod::create($start, $end);
        $hol = array_flip($holidayDates);
        $count = 0;
        foreach ($period as $day) {
            if ($skipWeekends && in_array($day->dayOfWeekIso, [6,7])) continue; // Sat(6)/Sun(7)
                    if (isset($hol[$day->toDateString()])) continue;
                     $count++;
                    }
        return max(1, $count);
        //return $count;
        }

} 