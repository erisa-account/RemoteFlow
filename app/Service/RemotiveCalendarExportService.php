<?php
namespace App\Service;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeStatusCalendarExport;
use App\Service\RemotiveFilterService;
use App\Service\GetMeLejeService;

class RemotiveCalendarExportService
{
    private RemotiveFilterService $remotiveFilterService;

    public function __construct(RemotiveFilterService $remotiveFilterService)
    {
        $this->remotiveFilterService = $remotiveFilterService;
    }

     public function exportStatusCalendar(array $filters)
     {
        /*dd([
        'filters_received' => $filters,
        'preset' => $filters['preset'] ?? null,
        'start_date' => $filters['start_date'] ?? null,
        'end_date' => $filters['end_date'] ?? null,
        'user_id' => $filters['user_id'] ?? null,
        'status_id' => $filters['status_id'] ?? null,
    ]);*/

       
    [$start, $end] = $this->remotiveFilterService->resolveDateRange(
        $filters['preset'] ?? null,
        $filters['start_date'] ?? null,
        $filters['end_date'] ?? null
    );

    // Determine status name from ID
    $statusName = null;
    if (!empty($filters['status_id'])) {
        $statusName = \App\Models\Status::find($filters['status_id'])->name ?? null;
        
    }

    // Get filtered records
    if (strtolower($statusName) === 'me leje') {
        $records = app(GetMeLejeService::class)->getFilteredLeaveRequestTable(
            $filters['user_id'] ?? null,
            $filters['preset'] ?? null,
            $start->toDateString(),   
            $end->toDateString() 
        );
    } else {
        $records = $this->remotiveFilterService->getFilteredRemotiveTable(
            $filters['user_id'] ?? null,
            $filters['status_id'] ?? null,
            $filters['preset'] ?? null,
            $start->toDateString(),
            $end->toDateString()
        );
    }

    // Build date columns
    $period = CarbonPeriod::create($start, $end);
    $dates = array_map(fn($d) => $d->toDateString(), iterator_to_array($period));

    // Pivot data
    [$exportData] = $this->buildEmployeeDayMatrix($records, $dates);

    $filename = sprintf(
        'employee-status-%s-%s.xlsx',
        $start->format('Ymd'),
        $end->format('Ymd')
    );

    return Excel::download(new EmployeeStatusCalendarExport($exportData, $dates), $filename);
}

     public function buildEmployeeDayMatrix(iterable $records, array $dates): array
    {
           // Group latest record per (user,date)
            $byUser = [];
            foreach ($records as $r) {
            $userId  = is_array($r) ? ($r['user_id'] ?? null) : ($r->user_id ?? null);
            $userName = is_array($r) ? ($r['user_name'] ?? ('User '.$userId))
            : ($r->user_name ?? ($r->user->name ?? ('User '.$userId)));
            $date = is_array($r) ? ($r['date'] ?? null) : ($r->date ?? null);
            

            if (!$userId || !$date) continue;

                $date = Carbon::parse($date)->toDateString();

                $byUser[$userId] ??= ['name' => $userName, 'days' => []];

                // Extract just the status string
                $status = null;
                if (is_array($r)) {
                    if (isset($r['status'])) {
                        $status = strtolower($r['status']); // if status is a key
                    } elseif (isset($r['status_name'])) {
                        $status = strtolower($r['status_name']);
                    } elseif (isset($r['status']['status'])) {
                        $status = strtolower($r['status']['status']);
                    }
                } else {
                    if (isset($r->status)) {
                        if (is_object($r->status) && isset($r->status->status)) {
                            $status = strtolower($r->status->status);
                        } else {
                            $status = strtolower($r->status);
                        }
                    } elseif (isset($r->status_name)) {
                        $status = strtolower($r->status_name);
                    }
                }

                $updated = is_array($r) ? ($r['updated_at'] ?? now()) : ($r->updated_at ?? now());

            $existingUpdated = $byUser[$userId]['days'][$date]['updated_at'] ?? null;
            if (!isset($byUser[$userId]['days'][$date]) || ($updated && $updated > $existingUpdated)) {
            $byUser[$userId]['days'][$date] = ['status' => $status, 'updated_at' => $updated];
            }
            }

        // Flatten to export rows
        $exportData = [];
        foreach ($byUser as $uid => $info) {
        $line = [
        'employee_id'  => $uid,
        'employee_name' => $info['name'],
        'days' => [],
        ];
        foreach ($dates as $d) {
        $line['days'][$d] = $info['days'][$d]['status'] ?? null;
        }
         $exportData[] = $line;
 }

         return [$exportData];
 }
} 







