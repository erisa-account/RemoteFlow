<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GetRemotiveFilterTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            'status_id' => 'nullable|integer|exists:status,id',
            'preset' => 'nullable|string|in:yesterday,7,30,last_week,last_month,last_year,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date', 
        ];
    }
}