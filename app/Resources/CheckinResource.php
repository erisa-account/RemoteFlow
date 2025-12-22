<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckinResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status_id' => $this->status_id,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ];
    }


    public function withResponse($request, $response)
    {
        $isExisting = !$this->wasRecentlyCreated;

        $response->setData([
        'success' => true,
        'existing' => $isExisting, 
        'message' => $isExisting
            ? 'You have already checked in for this date.'
            : 'Check-in saved successfully!',
        'data' => $response->getData()->data,
    ]);
}

}