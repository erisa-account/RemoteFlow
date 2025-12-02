<?php
namespace App\Http\Requests\Admin; 

use Illuminate\Foundation\Http\FormRequest;

class RejectLeaveRequestRequest extends FormRequest 
{ 
    
    public function authorize(): bool
    {
        logger([
        'user' => $this->user(),
        'cookies' => request()->cookies->all(),
        'headers' => request()->headers->all(),
    ]);
    return $this->user() && $this->user()->is_admin == 1;
    }
    
    public function rules(): array { 
        return ['reason' => ['required','string','max:2000']]; 
    } 
}