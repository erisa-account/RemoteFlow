<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveLeaveRequestRequest;
use App\Http\Requests\Admin\RejectLeaveRequestRequest;
use App\Service\ApprovalService;
use App\Models\LeaveRequest;
use App\Resources\AdminLeaveResource;
use App\Service\LeaveService;
use Illuminate\Http\Request;

class LeavesController extends Controller
{
    protected $approvalService;
    protected $leaveService;

    public function __construct(ApprovalService $approvalService, LeaveService $leaveService){
        $this->approvalService = $approvalService;
        $this->leaveService = $leaveService;
    }
    

    public function approve(ApproveLeaveRequestRequest $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        $result = $this->approvalService->approve($leaveRequest, $request->user()->id);

        return response()->json(['message' => 'Leave approved.', 'data' => $result]);
    }

    public function reject(RejectLeaveRequestRequest $request,  $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $result = $this->approvalService->reject($leaveRequest, $request->user()->id, $request->reason);

        return response()->json(['message' => 'Leave rejected.', 'data' => $result]);
    }



    public function getLeaves(Request $request)
    {
        $filters = $request->only(['status', 'user']);

        $leaves = $this->leaveService->getLeaves($filters);

    

        return AdminLeaveResource::collection($leaves);
    }

}
