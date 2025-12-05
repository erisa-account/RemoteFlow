<?php
namespace App\Service;

use App\Models\DayMarker;
use App\Models\Holidays;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
            'color' => '#4fffc4ff',
        ]);
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