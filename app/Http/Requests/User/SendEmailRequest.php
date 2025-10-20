<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file.*' => 'nullable|file|max:10240', // 10MB per file
        ];
    }

   
}