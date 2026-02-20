<?php

namespace App\Jobs;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\LeaveRequestStatusMail;
use Illuminate\Support\Facades\Mail;

class SendLeaveStatusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LeaveRequest $leave;
    protected string $status;

    public function __construct(LeaveRequest $leave, string $status)
    {
        $this->leave = $leave;
        $this->status = $status;
    }

    public function handle()
    {
        try {
        Mail::to($this->leave->user->email)->send(
            new LeaveRequestStatusMail(
                $this->status,
                $this->leave->user->name,
                $this->leave->start_date->format('Y-m-d'),
                $this->leave->end_date->format('Y-m-d'),
                $this->leave->approver?->name ?? 'Admin',
                $this->leave->type->display_name,
            )
        );
    } catch (\Exception $e) {
        \Log::error("Failed to send {$this->status} email", [
            'leave_id' => $this->leave->id,
            'error' => $e->getMessage(),
        ]);
    }
    }
}
