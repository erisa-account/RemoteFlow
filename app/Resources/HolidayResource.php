<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource{

    public function toArray($request){
        return [
            'id'=> $this->id,
            'date'=>$this->date->format('Y-m-d'),
            'name'=>$this->name,
            'country_code'=>$this->country_code,
            //'color'=>'#f87171',
        ];
    }
}