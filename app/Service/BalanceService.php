<?php
namespace App\Service;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    public function getOrCreate(int $userId, int $year, int $defaultTotal = 20): LeaveBalance
    {
        return LeaveBalance::firstOrCreate(
         ['user_id' => $userId, 'year' => $year],
         ['total_days' => $defaultTotal]
        );
    } 

        public function applyApproval(LeaveRequest $r): void
        {
            // Only count if paid type and NOT using comp time
            if (!$r->type->is_paid || $r->is_replacement) return;
          

            $year = (int) Carbon::parse($r->start_date)->format('Y');
            $balance = $this->getOrCreate($r->user_id, $year);

        
                    $balance->increment('used_days', $r->days);
               
            
            }
        

        public function revertApproval(LeaveRequest $r): void
        {
            if (!$r->type->is_paid || $r->is_replacement) return;

            $year = (int) Carbon::parse($r->start_date)->format('Y');
            $balance = $this->getOrCreate($r->user_id, $year);

        
            $balance->decrement('used_days', $r->days);

                if ($balance->used_days < 0) 
                        $balance->used_days = 0;
                        $balance->save();
}
            
    

         public function getLeaveSummary(int $userId, int $year): array
            {

                $balance = $this->getOrCreate($userId, $year);

                
                //dd($balance);
                return [
                    'total_days' => $balance->total_days,
                    'used_days' => $balance->used_days,
                    'remaining_days' => $balance->total_days - $balance->used_days,
                ];
            } 
}