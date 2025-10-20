<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\RemotiveFilterService;
use App\Resources\RemotiveUsersResource;
use App\Resources\RemotiveFilterResource; 
use App\Http\Requests\Admin\GetRemotiveFilterTableRequest;


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

    public function getRemotiveFilteredTable(GetRemotiveFilterTableRequest $request)
    {
    $data = $request->validated();

    $remotives = $this->remotiveFilterService->getFilteredRemotiveTable(
        $data['user_id'] ?? null,
        $data['status_id'] ?? null,
        $data['preset'] ?? null,
        $data['start_date'] ?? null,
        $data['end_date'] ?? null
    );

    return RemotiveFilterResource::collection($remotives);
    }

}   
