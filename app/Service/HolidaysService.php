<?php
namespace App\Service;

use App\Models\Holidays;
use App\Resources\HolidayResource;

class HolidaysService {
   
    public static function allHolidays(){
        
        return Holidays::orderBy('date')->get();
    }
}