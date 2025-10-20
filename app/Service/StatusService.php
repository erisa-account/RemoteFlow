<?php
namespace App\Service;

use App\Models\Status;
use App\Models\Remotive; 
  
class StatusService
{
    public function getAll()
    {
        return Status::all(['id', 'status']);
    }
    
    public function getStatusesNotOnSite()
    {
        return Remotive::with(['user', 'status'])
            ->where('status_id', '!=', 1)
            ->get(['id', 'user_id', 'status_id', 'date']);
    }



    
} 

