<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetRemotiveFilterTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }
    protected function prepareForValidation()
{
    
 \Log::info('Raw input', [
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'preset' => $this->preset
    ]);
    $convertDMYtoISO = function ($date) {
        if (!$date) return null;

        $parts = explode('/', $date); // for DD/MM/YYYY
        if (count($parts) === 3) {
            return "{$parts[2]}-{$parts[1]}-{$parts[0]}"; // YYYY-MM-DD
        }
        return $date; // already correct
    };
   $this->merge([
            'start_date' => $convertDMYtoISO($this->start_date),
            'end_date'   => $convertDMYtoISO($this->end_date),
        ]);

}

    public function rules(): array
    {
        $rules = [
        'user_id' => 'nullable|integer|exists:users,id',
        'status_id' => 'nullable|integer|exists:status,id',
        'preset' => 'nullable|string|in:yesterday,7,30,last_week,last_month,last_year,custom',
    ];

    // Only validate start/end dates if preset is custom
    if ($this->input('preset') === 'custom') {
        $rules['start_date'] = 'required|date';
        $rules['end_date']   = 'required|date|after_or_equal:start_date';
    }

    return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        // throw exception instead of redirect
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}