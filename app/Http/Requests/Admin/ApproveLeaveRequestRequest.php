<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveLeaveRequestRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'comment' => ['nullable','string','max:1000'], // optional comment on approval
        ];
    }
}