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

        
        $holidaysService = app(HolidaysService::class);

        $allOffDays = $holidaysService->getWeekendHolidays();

        $allCompensated = array_map(function($date) {
            return [
                'date' => $date,
                'name' => "Weekend Holiday",
                ];
        }, $allOffDays);

        return response()->json(['data' => $allCompensated]);
    }

    
}