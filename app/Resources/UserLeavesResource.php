<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLeavesResource extends JsonResource {

    public function toArray($request){
    return [
    'id' => $this->id,
    'user_id' => $this->user_id,
    'user_name' => $this->user->name ?? null,
    'leave_type_name' => $this->type->display_name ?? null,
    'start_date' => $this->start_date,
    'end_date' => $this->end_date,
    'days' => $this->days,
    'status' => $this->status,
    'approver_id' => $this->approver_id, 
    'approved_at' => $this->approved_at,
    'rejected_at' => $this->rejected_at,
    'rejection_reason' => $this->rejection_reason,
    'reason' => $this->reason,
    'medical_certificate_path' => $this->medical_certificate_path ? url('storage/' .$this->medical_certificate_path) :null,
];
    }
}