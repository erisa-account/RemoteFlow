<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\LeaveHistoryService;
use App\Resources\UserLeavesResource;

class UserLeavesController extends Controller
{
    protected $leavehistoryService;

    public function __construct(LeaveHistoryService $leavehistoryService)
    {
         $this->leavehistoryService = $leavehistoryService;
    }
 
    public function getUserLeaves()
    {
        $userId = auth()->id();
        $requests = $this->leavehistoryService->getAll($userId);

        return UserLeavesResource::collection($requests);
    }
}