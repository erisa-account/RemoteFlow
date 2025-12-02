<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\HolidaysService;
use App\Resources\HolidayResource;
use App\Service\DayMarkerService;

class HolidaysController extends Controller {
      
    public function index(){
        
        $holidays = HolidaysService::allHolidays();
        return HolidayResource::collection($holidays);
      
    }

    public function weekendHolidays() {

        $holidays = HolidaysService::allHolidays();
        $holidayDates = $holidays->pluck('date')->toArray();

        $compensationDays = app(DayMarkerService::class)->getWeekendHolidays($holidayDates);

        $allCompensated = [];
        foreach ($compensationDays as $d) {
            $allCompensated[] = [
                'date' => $d,
                'name' => 'Weekend Holiday',
                'color' => '#9bda49ff',
            ];
        }
        return response()->json(['data' => $allCompensated]);
    }

    
}