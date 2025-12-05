<?php
namespace App\Service;

use App\Models\Holidays;
use App\Resources\HolidayResource;
use App\Models\DayMarker;
use Carbon\Carbon;

class HolidaysService {
   
    public static function allHolidays(){
        
        return Holidays::orderBy('date')->get();
    }



    public function getHolidaysDatesForYear() : array {
         

        $year = now()->year;
       
        $holidays = $this->allHolidays();
        $dates = [];

        foreach ($holidays as $h) {
            $d = \Carbon\Carbon::parse($h->date);
            $dates[] = \Carbon\Carbon::create($year, $d->month, $d->day)->toDateString();

        }

        return $dates;
    }


    public function getWeekendHolidays(): array {
        $year = now()->year;
    
       
        $holidayDates = $this->getHolidaysDatesForYear();
        $weekendOffs = [];

        //$off = []; 
 
        foreach ($holidayDates as $date) {
            $d = \Carbon\Carbon::parse($date);

            if($d->isSaturday()) {
                $weekendOffs[] = $d->copy()->addDays(2)->toDateString();
            }

            if($d->isSunday()) {
                $weekendOffs[] = $d->copy()->addDay()->toDateString();
            }
        }

        

        //$allOffDays = [];
        

        return $weekendOffs;
    }

    public function getAllOffDaysForYear(): array {
        $year = now()->year;

        $holidays = $this-> getHolidaysDatesForYear();
        $weekendSubs = $this-> getWeekendHolidays();

        return array_unique(array_merge($holidays, $weekendSubs));
    }

    
    /*public function markWeekendHolidays(int $userId): array {
        //$offDays  = $this->getWeekendHolidays($holidayDates);

        $allOffDays = $this->getWeekendHolidays();

        foreach ($allOffDays as $offDate) {
            DayMarker::updateOrCreate(
                ['user_id' => $userId, 'date' => $offDate, 'status' => 'WEEKEND_HOLIDAY'],
                [
                    'note' => 'Off day due to weekend holiday',
                    'color' => '#8e8bc8ff',
                ]
                );
        }

        return $allOffDays;
    }*/

} 