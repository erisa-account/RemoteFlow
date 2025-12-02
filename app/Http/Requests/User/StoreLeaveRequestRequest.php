<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\LeaveType;
use App\Service\OverLapValidator;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
        'leave_type_id' => ['required','integer','exists:leave_types,id'],
        'start_date'  => ['required','date'],
        'end_date' => ['required','date','after_or_equal:start_date'],
        'is_replacement' => ['boolean'],
        'medical_certificate' => ['nullable','file','mimes:pdf','max:10240'],
        'reason' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
        $type = LeaveType::find($this->input('leave_type_id'));
        if ($type && $type->requires_document && !$this->file('medical_certificate')) {
            $v->errors()->add('medical_certificate', 'Medical certificate (PDF) is required for this leave type.');
        }

        $overlapValidator = app(OverlapValidator::class);
        $userId = $this->user()->id;
        $start = $this->start_date;
        $end = $this->end_date;
        $typeId = $this->input('leave_type_id');

        if($typeId !=4) {
         if($overlapValidator->hasOverlap($userId, $start, $end)) {
            $v->errors()->add('start_date', 'You already have a leave request during this period.');
         } 
        }
        else {
            if ($overlapValidator->hasOverlapReplacment($userId, $start, $end)) {
                $v->errors()->add('start_date', 'You already have replacment request during this period.');
            } 
        }

       
        });
    }
}