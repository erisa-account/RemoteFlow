<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remotive;
use Illuminate\Support\Facades\Session;
use App\Models\Status; 

class CheckinController extends Controller
{
public function create()
{
    $statusList = Status::all(); // get all statuses from the status table
    return view('forms', compact('statusList')); // pass to the view
}
    public function store(Request $request)
    {
        // Get user_id from session
        $user_id = Session::get('user_id'); 
        if (!$user_id) {
            return response()->json(['message' => 'User not logged in.'], 401);
        }

        // Validate inputs
        $request->validate([
            'status_id' => 'required|exists:status,id',
            'date' => 'required|date',
        ]);

        // Check if record exists
        $remotive = Remotive::where('user_id', $user_id)
                    ->where('date', $request->date)
                    ->first();

        if ($remotive) {
            $remotive->status_id = $request->status_id;
            $remotive->save();

            return response()->json(['message' => 'Status updated successfully.']);
        }

        // Create new record
        Remotive::create([
            'user_id' => $user_id,
            'status_id' => $request->status_id,
            'date' => $request->date,
        ]);

        return response()->json(['message' => 'Check-in saved successfully.']);
    }
}
