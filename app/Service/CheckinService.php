<?php
namespace App\Service;

use App\Models\Remotive;
use App\Http\Requests\User\StoreCheckinRequest;
use Illuminate\Support\Facades\Auth;


class CheckinService
{

    public function storeData(StoreCheckinRequest $request): void
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        // Këtu mund të shtosh kontrolle për dublikatë nëse do
        Remotive::create($data);
    }
}