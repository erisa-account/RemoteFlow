<?php 
namespace App\Service;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Http\Requests\User\StoreLeaveRequestRequest;
use Illuminate\Support\Facades\Auth; 
use App\Resources\LeaveRequestResource;

class LeaveRequestService {
public function store(StoreLeaveRequestRequest $request)
{
    $data = $request->validated();

    // Optional: calculate days before storing
    // $days = app(LeaveCalculator::class)->businessDays($data['start_date'], $data['end_date']);

    $leaveType = LeaveType::findOrFail($data['leave_type_id']);

    $leave = LeaveRequest::create([
        'user_id' => auth()->id(),
        'leave_type_id' => $data['leave_type_id'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'days' => 0, // or $days if calculated
        'reason' => $data['reason'],
        'uses_comp_time' => $data['uses_comp_time'] ?? false,
        'status' => 'pending',
    ]);

    // Handle file upload
    if ($file = $request->file('medical_certificate')) {
        $path = $file->store('medical_certificates');
        $leave->update(['medical_certificate_path' => $path]);
    }

    return $leave;
}
}