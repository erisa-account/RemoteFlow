<?php 
namespace App\Service;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Http\Requests\User\StoreLeaveRequestRequest;
use Illuminate\Support\Facades\Auth; 
use App\Resources\LeaveRequestResource;
use App\Service\LeaveCalculator;



class LeaveRequestService {
public function store(StoreLeaveRequestRequest $request)
{
    $data = $request->validated();

  
    

    $holidayService = app(\App\Service\HolidaysService::class);
 
    $offDays = $holidayService->getAllOffDaysForYear();

    $leaveType = LeaveType::findOrFail($data['leave_type_id']);

    // Optional: calculate days before storing
    $days = app(LeaveCalculator::class)->businessDays($data['start_date'], $data['end_date'], $offDays);

    
    if($leaveType->id === 4){
        $days = 1;
    }

    
   
    

    

    $leave = LeaveRequest::create([
        'user_id' => auth()->id(),
        'leave_type_id' => $data['leave_type_id'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'days' => $days,
        
        'reason' => $data['reason'],
        //'uses_comp_time' => $data['uses_comp_time'] ?? false,
        'is_replacement' => $leaveType -> id === 4,
        'status' => 'pending',
    ]);

    // Handle file upload
    if ($file = $request->file('medical_certificate')) {
        $path = $file->store('medical_certificates', 'public');
        $leave->update(['medical_certificate_path' => $path]);
    }

    return $leave;
}
}