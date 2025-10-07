<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\RemotiveFilterService;
use App\Resources\RemotiveUsersResource;
use App\Resources\RemotiveFilterResource; 


class RemotiveFilterController extends Controller 
{
    protected $remotiveFilterService; 

    public function __construct(RemotiveFilterService $remotiveFilterService)
    {
        $this->remotiveFilterService = $remotiveFilterService;
    }

    public function getUserName()
    {
        $users = $this->remotiveFilterService->getUserName();
        return RemotiveUsersResource::collection($users);
    }

    public function getRemotiveTable()
    { 
        $remotives = $this->remotiveFilterService->getRemotiveTable(); 
        return RemotiveFilterResource::collection($remotives);
    } 
}   
