<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreLeaveRequestRequest;
use App\Resources\LeaveRequestResource;
use App\Service\LeaveRequestService;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Holiday; 
use App\Service\LeaveCalculator;
use App\Service\OverLapValidator; 
use App\Service\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class RequestLeaveController extends Controller
{
    protected  $leaverequestService;

    public function __construct(LeaveRequestService $leaverequestService) {
        $this->leaverequestService = $leaverequestService;
    }

    public function storerequest(StoreLeaveRequestRequest $request)
    {
        // Validation is already done in StoreLeaveRequestRequest
        $leave = $this->leaverequestService->store($request);

        return new LeaveRequestResource($leave->load(['type','user']));
    }
}