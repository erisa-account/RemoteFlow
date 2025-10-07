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
            'status_id'  => $this->status_id,
            'date'       => $this->date,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
