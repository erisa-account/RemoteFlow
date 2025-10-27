<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemotiveFilterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'user_name'  => $this->user?->name,
            'status_id'  => $this->status_id,
            'status_name'=> $this->status?->status,
            'date'       => $this->date,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
} 