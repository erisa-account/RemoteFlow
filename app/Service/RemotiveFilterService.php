<?php
namespace App\Service;

use App\Models\User;
use App\Models\Remotive;

class RemotiveFilterService
{
    public function getUserName(){

    return User::all();
    //return User::select('id', 'name')->get();
    }

    public function getRemotiveTable()
    {
    return Remotive::select('id', 'user_id', 'status_id', 'date', 'created_at', 'updated_at')->get();
    }
}