<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreCheckinRequest;
use App\Service\CheckinService;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    protected $checkinService;

    public function __construct(CheckinService $checkinService)
    {
        $this->checkinService = $checkinService;
    }

    public function store(StoreCheckinRequest $request)
    {
        
        $this->checkinService->storeData($request);

        //return redirect()->back()->with('success', 'Check-in u ruajt me sukses!');
    }
}
