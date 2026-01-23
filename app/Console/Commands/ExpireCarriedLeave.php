<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveBalance;

class ExpireCarriedLeave extends Command
{
    protected $signature = 'leave:expire-carry';

    public function handle()
    {
        LeaveBalance::where('year', now()->year)
            ->update(['carried_over_days' => 0]);
    }
}