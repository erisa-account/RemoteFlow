<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusesNotSiteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => ($this->user?->name ?? 'Unknown') . ' — ' . ($this->status?->status ?? 'Unknown'),
            'start' => $this->date,
            'color' => $this->status?->status === 'remote' ? '#3b82f6' : '#a91ba9ff',
            'extendedProps' => [
                'userName' => $this->user?->name,
                'statusName' => $this->status?->status,
            ],
        ];
    }
}

