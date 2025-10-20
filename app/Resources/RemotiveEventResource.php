<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemotiveEventResource extends JsonResource
{
    public function toArray($request)
    {
        $color = match ($this->status?->slug) {
            'onsite'  => '#10b981',
            'remote'  => '#3b82f6',
            'meleje'     => '#9ca3af',
            default   => '#6b7280',
        };

        return [
            'id'    => $this->id,
            'title' => $this->user?->name . ' — ' . ($this->status?->status ?? 'Unknown'),
            'start' => \Illuminate\Support\Carbon::parse($this->date)->toDateString(),
            'allDay' => true,
            'color' => $color,
            'extendedProps' => [
                'userName'   => $this->user?->name,
                'statusName' => $this->status?->status, 
            ],
        ];
    }
}