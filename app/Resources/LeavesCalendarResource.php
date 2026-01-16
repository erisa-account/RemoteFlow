<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class LeavesCalendarResource extends JsonResource
{
   public function toArray($request)
    {
        return [
            'title' => $this->user?->name . ' — ' . ($this->type?->name ?? 'leave'),

            'start' => $this->start_date,
            'end'   => $this->end_date,

            // violet color for all calendar leaves
            'color' => '#a91ba9ff',

            'extendedProps' => [
                'userName'  => $this->user?->name,
                'statusName'=> 'leave',
                'leaveType' => $this->type?->name ?? 'leave',
            ],
        ];
    }
}