<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemotiveUsersResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}