<?php
namespace App\Service;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestStatusMail;

class ApprovalService
{
    protected BalanceService $balance;

    public function __construct(BalanceService $balance)
    {
        $this->balance = $balance;
    }

    public function approve(LeaveRequest $req, int $approverId): LeaveRequest
    {
        return DB::transaction(function () use ($req, $approverId) {

            $req=$req->fresh();

            if($req->status==='approved') return $req;
            

            if (!in_array($req->status, ['pending', 'rejected'])) return $req;

        $req->forceFill([
        'status' => 'approved',
        'approver_id' => $approverId,
        'approved_at' => now(),
        'rejected_at' => null,
        'rejection_reason' => null,
        ])->save();

        $this->balance->applyApproval($req);

        $this->sendLeaveStatusEmail($req, 'approved');

        return $req->refresh();
        });
    }

        public function reject(LeaveRequest $req, int $approverId, string $reason): LeaveRequest
         {
            return DB::transaction(function () use ($req, $approverId, $reason) {

                $req = $req->fresh();
                
            if ($req->status === 'approved') {
            $this->balance->revertApproval($req);
            }

            $req->forceFill([
            'status' => 'rejected',
            'approver_id' => $approverId,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            ])->save();

            $this->sendLeaveStatusEmail($req, 'rejected');

            return $req->refresh();
            });
         }



        public function cancel(LeaveRequest $req): LeaveRequest
        {
            return DB::transaction(function () use ($req) {
            if ($req->status === 'approved') { 
            $this->balance->revertApproval($req);
        } 
            $req->update(['status'=>'cancelled']);
            return $req->refresh();
            });
        }



         protected function sendLeaveStatusEmail(LeaveRequest $req, string $status): void
        {
        try {
            Mail::to($req->user->email)->send(
                new LeaveRequestStatusMail(
                    $status,
                    $req->user->name,
                    $req->start_date->format('Y-m-d'),
                    $req->end_date->format('Y-m-d'),
                    $req->approver?->name ?? 'Admin',
                    $req->type->display_name,
                )
            );
        } catch (\Exception $e) {
            \Log::error("Failed to send {$status} email", [
                'leave_id' => $req->id,
                'error' => $e->getMessage(),
            ]);
        }
        }

}