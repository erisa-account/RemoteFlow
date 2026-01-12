<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeLejeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            //'user'        => $this->user->name,
            'user_name'   => $this->user?->name ?? 'Unknown user',
            'status_name' => $this->leave_type?->name ?? $this->status, 
            //'date'        => $this->start_date->format('Y-m-d') . '→' . $this->end_date->format('Y-m-d'),
            'date' => trim(str_replace(' → ', '→', $this->start_date->format('Y-m-d') . ' → ' . $this->end_date->format('Y-m-d'))),
            'days'        => $this->days,
            'status'      => $this->status,
            'created_at'  => $this->created_at->format('Y-m-d'),
            'updated_at'  => $this->updated_at->format('Y-m-d'),
        ];
    }
}