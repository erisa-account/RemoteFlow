<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Service\GetMeLejeService;
use App\Models\LeaveRequest;
use App\Models\Remotive;
use Illuminate\Http\Request;
use App\Resources\MeLejeResource;




class MeLejeController extends Controller {


     protected $getmelejeService;

    public function __construct(GetMeLejeService $getmelejeService)
    {
        $this->getmelejeService = $getmelejeService;
    }


    public function getStatusMeLeje(Request $request)
    {
        $leaves = $this->getmelejeService->getMeLeje();

         foreach ($leaves as $leave) {
        dd($leave->user); // <--- this will stop execution at the first leave
    };

        return response()->json(
            
            
        );
    }

    public function getStatusMeLejeFiltered(Request $request)
    {

    $leaves = $this->getmelejeService->getFilteredLeaveRequestTable(
        $request->user_id,
        $request->status,
        $request->preset,
        $request->start_date,
        $request->end_date
    );

     return MeLejeResource::collection($leaves);
    }
}