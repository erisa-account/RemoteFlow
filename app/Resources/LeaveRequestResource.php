<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;


class LeaveRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'type' => $this->type->id,
            'typeName' => $this->type->display_name,
            'status'    => $this->status,
            'start' => Carbon::parse($this->start_date)->toDateString(),
            'end'   => Carbon::parse($this->end_date)->toDateString(),
            'days'      => $this->days,
            'reason'    => $this->reason,
            'is_replacement' => (bool) $this->is_replacement,
            'createdAt'  => $this->created_at?->toIso8601String(),
            'approvedAt' => $this->approved_at?->toIso8601String(),
        ];
    }
}