<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreLeaveRequestRequest;
use App\Resources\LeaveRequestResource;
use App\Resources\LeaveSummaryResource;
use App\Service\LeaveRequestService;
use App\Service\BalanceService;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Holiday; 
use App\Service\LeaveCalculator;
use App\Service\OverLapValidator; 
use App\Service\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class RequestLeaveController extends Controller
{
    protected  $leaverequestService;
    protected $balanceService;

    public function __construct(LeaveRequestService $leaverequestService, BalanceService $balanceService) {
        $this->leaverequestService = $leaverequestService;
        $this->balanceService = $balanceService;
    }
 

    public function storerequest(StoreLeaveRequestRequest $request)
    {
        // Validation is already done in StoreLeaveRequestRequest
        $leave = $this->leaverequestService->store($request);

        return new LeaveRequestResource($leave->load(['type','user']));
    }

    public function getLeaveSummary(Request $request)
        {
             $userId = $request->user()->id;
             $year = now()->year;

            $leaveSummary = $this->balanceService->getLeaveSummary($userId, $year);

            return new LeaveSummaryResource($leaveSummary);
        }


         public function getLeaveData()
        {
            $userId = Auth::id();

            $data = $this->balanceService->getLeaveSummary($userId);

            if (!$data) {
                return response()->json([
                    'error' => 'Leave balance not found'
                ], 404);
            }

            return response()->json($data);
        }
        

        public function storeStartingDate(Request $request)
        {
            $request->validate([
                'starting_date' => ['required', 'date'],
            ]);

            $userId = Auth::id(); // logged-in user

            $balance = $this->balanceService->storeStartingDate(
            $userId,
            $request->starting_date
            );

            

            return redirect()->back()->with('success', 'Starting date saved successfully');
        }

} 