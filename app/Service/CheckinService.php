<?php
namespace App\Service;

use App\Models\Remotive;
use App\Http\Requests\User\StoreCheckinRequest;
use Illuminate\Support\Facades\Auth;


class CheckinService 
{

   public function storeData(StoreCheckinRequest $request) :Remotive 
{
    $data = $request->validated(); 
    $data['user_id'] = Auth::id();

    
    $existing = Remotive::where('user_id', $data['user_id'])
                            ->where('date', $data['date'])
                            ->first();

    return $existing ?: Remotive::create($data);

        // Create new check-in
        //Remotive::create($data);

    }

    public function updateStatus($id, $newStatusId): Remotive
    {
            $checkin = Remotive::findOrFail($id);
            $checkin->status_id = $newStatusId;
            $checkin->save();
       
            return $checkin->fresh();
    }


}