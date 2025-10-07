<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreCheckinRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'status_id' => 'required|exists:status,id',
            'date' => 'required|date|after_or_equal:today',
        ];
    } 
    protected function prepareForValidation()
    {
        if (!empty($this->date)) {
            $this->merge([
                'date' => Carbon::parse($this->date)->format('Y-m-d')
            ]);
        }
    }
}