<?php
namespace App\Service;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;

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
        if (!in_array($req->status, ['pending'])) return $req;

        $req->forceFill([
        'status' => 'approved',
        'approver_id' => $approverId,
        'approved_at' => now(),
        ])->save();

        $this->balance->applyApproval($req);

        return $req->refresh();
        });
    }

        public function reject(LeaveRequest $req, int $approverId, string $reason): LeaveRequest
         {
            return DB::transaction(function () use ($req, $approverId, $reason) {
            if ($req->status === 'approved') {
            $this->balance->revertApproval($req);
            }

            $req->forceFill([
            'status' => 'rejected',
            'approver_id' => $approverId,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            ])->save();

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
}