<?php
namespace App\Http\Controllers\User;

use App\Models\DayMarker;
use App\Http\Controllers\Controller;

class DayMarkerController extends Controller
{
    public function index($userId)
    {
        return DayMarker::where('user_id', $userId)->get();
    }
}