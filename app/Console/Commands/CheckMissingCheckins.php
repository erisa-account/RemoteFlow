<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Remotive;
use Carbon\Carbon;
use App\Mail\CheckinMail;
use Illuminate\Support\Facades\Mail;

class CheckMissingCheckins extends Command
{
    protected $signature = 'checkins:missing';

    protected $description = 'Check users without check-in and create default On Site record';

    protected $service;

public function __construct(\App\Service\CheckinService $service)
{
    parent::__construct();
    $this->service = $service;
}

    public function handle()
    {
        $today = \Carbon\Carbon::today()->toDateString();
    

    $users = \App\Models\User::where('is_admin', 0)->get();
   

    foreach ($users as $user) {
        
        $exists = \App\Models\Remotive::where('user_id', $user->id)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            continue;
        }


       
            $this->service->storeDataForCron([
                'user_id' => $user->id,
                'status_id' => 1,
                'date' => $today,
            ]);
            


            Mail::to($user->email)
            ->send(new CheckinMail($user->name));
        
        }

    }
}