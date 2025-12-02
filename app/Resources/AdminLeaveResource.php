<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AdminLeaveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type->id ?? null,
            'typeName' => $this->type->display_name ?? 'Vacation',
            'status' => $this->status,
            'start' => Carbon::parse($this->start_date)->toDateString(),
            'end' => Carbon::parse($this->end_date)->toDateString(),
            'days' => ($this->days && $this->days > 0)
            ? $this->days
            : Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1,
            'reason' => $this->reason,
            'is_replacement' => (bool) $this->is_replacement,
            'createdAt' => $this->created_at? Carbon::parse($this->created_at)->toIso8601String() : null,
            'approvedAt' => $this->approved_at? Carbon::parse($this->approved_at)->toIso8601String() : null,
            'rejectedAt' => $this->rejected_at? Carbon::parse($this->rejected_at)->toIso8601String() : null,
            'rejectionReason' => $this->rejection_reason ?? null,
            'medical_certificate_path' => $this->medical_certificate_path ? url('storage/'.$this->medical_certificate_path) : null,

            // Employee info for admin dashboard
            'employee' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? 'Unknown',
                'role' => $this->user->role ?? '',
                'totalDays' => $this->user->leaveBalance?->total_days ?? 0,
                'usedDays' => $this->user->leaveBalance?->used_days ?? 0,

            ],
            
        ];
    }
}
