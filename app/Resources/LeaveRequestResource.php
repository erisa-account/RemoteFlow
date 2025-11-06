<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'type' => $this->type->id,
            'typeName' => $this->type->display_name,
            'status'    => $this->status,
            'start'     => $this->start_date->toDateString(),
            'end'       => $this->end_date->toDateString(),
            'days'      => $this->days,
            'reason'    => $this->reason,
            'usesCompTime' => (bool) $this->uses_comp_time,
            'createdAt'  => $this->created_at?->toIso8601String(),
            'approvedAt' => $this->approved_at?->toIso8601String(),
        ];
    }
}