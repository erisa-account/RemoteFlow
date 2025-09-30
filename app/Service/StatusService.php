<?php
namespace App\Service;

use App\Models\Status;
  
class StatusService
{
    public function getAll()
    {
        return Status::all(['id', 'status']);
    }

 
} 

