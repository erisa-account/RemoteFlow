<?php
namespace App\Service;

use App\Models\DayMarker;

class DayMarkerService
{
    public static function markWorkedHoliday($userId, $holidayDate)
    {
        DayMarker::updateOrCreate([
            'user_id' => $userId,
            'date'    => $holidayDate,
            'status'  => 'HOLIDAY_WORKED',
        ],[
            'note'  => 'Worked on public holiday',
            'color' => '#10b981',
        ]);
    }

    public function getWeekendHolidays(array $holidayDates): array {
        $off = [];

        foreach ($holidayDates as $date) {
            $d = \Carbon\Carbon::parse($date);

            if($d->isSaturday()) {
                $off[] = $d->copy()->addDays(2)->toDateString();
            }

            if($d->isSunday()) {
                $off[] = $d->copy()->addDay()->toDateString();
            }
        }

        if(count($off) === 2 && $off[0] === $off[1]) {
            $off[] = \Carbon\Carbon::parse($off[0])->addDay()->toDateString();
        }

        return array_unique($off);
    }

    public function markWeekendHolidays(int $userId, array $holidayDates): array {
        $offDays  = $this->getWeekendHolidays($holidayDates);

        foreach ($offDays as $offDate) {
            DayMarker::updateOrCreate(
                ['user_id' => $userId, 'date' => $offDate, 'status' => 'WEEKEND_HOLIDAY'],
                [
                    'note' => 'Off day due to weekend holiday',
                    'color' => '#8e8bc8ff',
                ]
                );
        }

        return $offDays;
    }





    public static function markReplacement($userId, $weekdayDate, $weekendDate, $leaveRequest)
    {
        DayMarker::updateOrCreate([
            'user_id' => $userId,
            'date'    => $weekdayDate,
            'status'  => 'REPLACEMENT_OFF',
        ],[
            'leave_request_id' => $leaveRequest->id,
            'note'  => "Replaced by {$weekendDate}",
            'color' => '#0ea5e9',
        ]);

        DayMarker::updateOrCreate([
            'user_id' => $userId,
            'date'    => $weekendDate,
            'status'  => 'REPLACEMENT_SOURCE',
        ],[
            'note'  => "Paid for weekday {$weekdayDate}",
            'color' => '#f59e0b',
        ]);
    }

    public function markReplacementOff(LeaveRequest $req, array $weekdayDates, ?string $sourceWeekendDate = null): void
    {
        foreach ($weekdayDates as $d) {
            DayMarker::updateOrCreate(
            ['user_id'=>$req->user_id,'date'=>$d,'status'=>'REPLACEMENT_OFF'],
            ['leave_request_id'=>$req->id]
            );
        }
        if ($sourceWeekendDate) {
            DayMarker::updateOrCreate(
            ['user_id'=>$req->user_id,'date'=>$sourceWeekendDate,'status'=>'REPLACEMENT_SOURCE'],
            ['note'=>'Paired with '.implode(',',$weekdayDates)]
            );
        }
    }
}