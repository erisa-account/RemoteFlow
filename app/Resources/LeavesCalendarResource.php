<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class LeavesCalendarResource extends JsonResource
{
   public function toArray($request)
    {
        // Determine the type of leave
        $isReplacement = $this->type?->id === 4; // 4 = replacement leave

        return [
            'title' => $this->user?->name . ' — ' . ($isReplacement ? 'replacement' : ($this->type?->name ?? 'leave')),
            'start' => $isReplacement ? $this->end_date : $this->start_date, // only the replacement day
            'end'   => $isReplacement ? $this->end_date : $this->end_date,   // same as start for replacement
            //'allDay'=> true,
            'color' => $isReplacement ? '#fbbf24' : ($this->type?->color ?? '#a91ba9ff'), 

            'extendedProps' => [
                'userName'  => $this->user?->name,
                'statusName'=> $isReplacement ? 'replacement' : 'leave',
                'leaveType' => $this->type?->name ?? 'leave',
            ],
        ];
    }
}