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
        'existing' => $isExisting, // ✅ this flag tells JS if it's old or new
        'message' => $isExisting
            ? 'Keni bërë tashmë check-in për këtë datë.'
            : 'Check-in u ruajt me sukses!',
        'data' => $response->getData()->data,
    ]);
}

}