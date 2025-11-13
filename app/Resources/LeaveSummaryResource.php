<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class LeaveSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'total_days' => $this['total_days'],
            'used_days' => $this['used_days'],
            'remaining_days' => $this['remaining_days'],
        ];
    }
}