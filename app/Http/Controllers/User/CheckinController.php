<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreCheckinRequest;
use App\Service\CheckinService;
use App\Resources\CheckinResource; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class CheckinController extends Controller
{
    protected $checkinService;

    public function __construct(CheckinService $checkinService)
    {
        $this->checkinService = $checkinService;
    } 

    public function store(StoreCheckinRequest $request)
    {   
        $remotive = $this->checkinService->storeData($request);
        return new CheckinResource($remotive);
    }
     public function update($id, Request $request)
    {
        $newStatusId = $request->input('status_id');
        $checkin = $this->checkinService->updateStatus($id, $newStatusId);
        return new CheckinResource($checkin);
    }
}
