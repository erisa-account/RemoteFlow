<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveBalance;

class ExpireCarriedLeave extends Command
{
    protected $signature = 'leave:expire-carry';
    protected $description = 'Expire carried over leave for current year';

    public function handle()
    {
        $year = now()->year;

        $affected = LeaveBalance::where('year', $year)
            ->where('carried_over_days', '>', 0)
            ->update([
                'carried_over_days' => 0,
                'carried_over_expired' => 1,
            ]);

        $this->info("Expired carried over leave for year {$year}");
        $this->info("Rows updated: {$affected}");
    }

}