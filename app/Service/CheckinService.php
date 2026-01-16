<?php
namespace App\Service;
 
use App\Models\Remotive;
use App\Http\Requests\User\StoreCheckinRequest;
use Illuminate\Support\Facades\Auth;


class CheckinService 
{

   public function storeData(StoreCheckinRequest $request) :Remotive 
{$data = $request->validated();
        $data['user_id'] = Auth::id();

        // If the user hasn't selected a status, default to "On Site"
        if (empty($data['status_id'])) {
            $data['status_id'] = 1; // <-- Replace 1 with your actual "On Site" status ID
        }

        // Check if a record for this user and date already exists
        $existing = Remotive::where('user_id', $data['user_id'])
                            ->where('date', $data['date'])
                            ->first();

        return $existing ?: Remotive::create($data);

    }

    public function updateStatus($id, $newStatusId): Remotive
    {
            $checkin = Remotive::findOrFail($id);
            $checkin->status_id = $newStatusId;
            $checkin->save();
       
            return $checkin->fresh();
    }


}