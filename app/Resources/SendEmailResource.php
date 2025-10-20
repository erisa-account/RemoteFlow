<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SendEmailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'message' => 'Email sent successfully!',
            'email' => $this['email'],
            'subject' => $this['subject'],
            'description' => $this['description'] ?? 'No description provided',
            'attachments' => $this['attachments'],
        ]; 
    }
}