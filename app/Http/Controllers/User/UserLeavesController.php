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
        $requests = $this->leavehistoryService->getAll();

        return UserLeavesResource::collection($requests);
    }
}