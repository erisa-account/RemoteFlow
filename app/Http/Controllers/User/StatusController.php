<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\StatusService;
//use App\Resources\RemotiveEventResource; 
use App\Resources\StatusesNotSiteResource;


class StatusController extends Controller 
{
    protected $statusService;

    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function index()
    {
        $statuses = $this->statusService->getAll();
        return response()->json($statuses);
    }
    /*public function events()
    {
           $remotives = $this->remotiveFilterService->getRemotiveTable();
           return RemotiveEventResource::collection($remotives);
    }*/
    public function getStatusesNotOnSite()
    {
        $statuses = $this->statusService->getStatusesNotOnSite();
        return StatusesNotSiteResource::collection($statuses);
    }

}