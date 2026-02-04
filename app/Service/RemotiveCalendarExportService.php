<?php
namespace App\Service;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeStatusCalendarExport;
use App\Service\RemotiveFilterService;
use App\Service\GetMeLejeService;
use App\Models\LeaveRequest;
use App\Exports\MultiMonthEmployeeStatusExport;

class RemotiveCalendarExportService
{
    private RemotiveFilterService $remotiveFilterService;
    private GetMeLejeService $getmelejeService;

    public function __construct(RemotiveFilterService $remotiveFilterService, GetMeLejeService $getmelejeService)
    {
        $this->remotiveFilterService = $remotiveFilterService;
        $this->getmelejeService = $getmelejeService;
    }

     public function exportStatusCalendar(array $filters)
     {
        //throw new \Exception('Excel reached service');

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
    \Log::info('EXCEL FILTER DATES', [
    'raw_start' => $start,
    'raw_end'   => $end,
    'start_type'=> gettype($start),
    'end_type'  => gettype($end),
]);

    /*$start = $start instanceof \Carbon\Carbon ? $start : \Carbon\Carbon::parse($start);
    $end   = $end instanceof \Carbon\Carbon ? $end : \Carbon\Carbon::parse($end);*/

    $start = Carbon::parse($start);
$end   = Carbon::parse($end);

\Log::info('EXCEL FILTER DATES AFTER PARSE', [
    'start' => $start->toDateString(),
    'end'   => $end->toDateString(),
]);

    // Determine status name from ID
    $statusName = null;
    $statusId = intval($filters['status_id'] ?? 0);

$statusName = \App\Models\Status::where('id', $statusId)->value('status');

\Log::info('Determined status name', [
    'status_id' => $statusId,
    'status_name' => $statusName,
]);

    // Get filtered records
    if (strtolower($statusName) === 'me leje') {
        \Log::info('Fetching leave requests for Excel');
                
    $records = $this->getmelejeService->getFilteredLeaveRequestTable(
        $filters['user_id'] ?? null,
        null,
        $filters['preset'] ?? null,
        $start,
        $end
    );

    $records = $records->filter(function($leave) {
        return $leave->status === 'approved' && $leave->leave_type_id != 4;
    });
    $records->each(function($leave) {
    \Log::info('Leave for Excel', [
        'id' => $leave->id,
        'user_id' => $leave->user_id,
        'start_date' => $leave->start_date,
        'end_date' => $leave->end_date,
        'status' => $leave->status,
        'leave_type_id' => $leave->leave_type_id,
    ]);
});

   $records = $records->flatMap(function ($leave) use ($start, $end) {
        $period = CarbonPeriod::create(
            Carbon::parse($leave->start_date)->max($start),
            Carbon::parse($leave->end_date)->min($end)
        );

        return collect($period)->map(function ($date) use ($leave) {
            return [
                'user_id'     => $leave->user_id,
                'user_name'   => $leave->user->name ?? 'User '.$leave->user_id,
                'date'        => $date->toDateString(), // ✅ REQUIRED
                'status_name' => 'leave',
                'updated_at'  => $leave->updated_at,
            ];
        });
    });
    
   
    } else {
        \Log::info('Fetching remotive table for Excel');
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

    \Log::info('Excel export data preview', [
    'records_count' => $records->count(),
    'dates_count'   => count($dates),
    'export_data_sample' => array_slice($exportData, 0, 5), // show first 5 rows
]);

// Optional: also log as JSON for clarity
\Log::info('Excel export full data', [
    'export_data_json' => json_encode($exportData),
]);

    $filename = sprintf(
        'employee-status-%s-%s.xlsx',
        $start->format('Ymd'),
        $end->format('Ymd')
    );
    /*dd([
    'start' => $start,
    'end' => $end,
    'records' => $records->count(),
]);*/

/*dd([
    'preset' => $filters['preset'] ?? null,
    'start' => $start,
    'end' => $end,
    'records_count' => $records->count(),
    'first_record' => $records->first()?->toArray() ?? null,
]);*/

\Log::info('EXCEL RAW RECORD DATES SAMPLE');

$records->take(20)->each(function ($r) {
    \Log::info('RECORD DATE', [
        'user' => $r['user_id'] ?? $r->user_id ?? null,
        'date' => $r['date'] ?? $r->date ?? null,
    ]);
});

$recordsByMonth = $records->groupBy(function($leave) {
    return Carbon::parse($leave['date'] ?? $leave->date)->format('Y-m'); // '2026-01'
});

\Log::info('EXCEL GROUPED MONTHS', [
    'months' => $recordsByMonth->keys()->toArray()
]); 


$dataByMonth = [];
foreach ($recordsByMonth as $month => $monthRecords) {
    $filterStart = $start->copy();
$filterEnd   = $end->copy();

foreach ($recordsByMonth as $month => $monthRecords) {

    $monthCarbon = Carbon::createFromFormat('Y-m', $month);

    // Default: full month
    $monthStart = $monthCarbon->copy()->startOfMonth();
    $monthEnd   = $monthCarbon->copy()->endOfMonth();

    // If first month → start from filter start
    if ($monthCarbon->isSameMonth($filterStart)) {
        $monthStart = $filterStart->copy();
    }

    // If last month → end at filter end
    if ($monthCarbon->isSameMonth($filterEnd)) {
        $monthEnd = $filterEnd->copy();
    }

    [$exportData, $dates] = $this->buildEmployeeDayMatrix(
        $monthRecords,
        array_map(
            fn($d) => $d->toDateString(),
            iterator_to_array(CarbonPeriod::create($monthStart, $monthEnd))
        )
    );

    \Log::info('MONTH RANGE DEBUG', [
    'month' => $month,
    'filter_start' => $start->toDateString(),
    'filter_end'   => $end->toDateString(),
    'month_start'  => $monthStart->toDateString(),
    'month_end'    => $monthEnd->toDateString(),
]);

    $dataByMonth[$month] = [$exportData, $dates];
}

    [$exportData, $dates] = $this->buildEmployeeDayMatrix(
        $monthRecords, 
        array_map(fn($d) => $d->toDateString(), iterator_to_array(CarbonPeriod::create($monthStart, $monthEnd)))
    );

    $dataByMonth[$month] = [$exportData, $dates];
}

$filename = sprintf('employee-status-%s-%s.xlsx', $start->format('Ymd'), $end->format('Ymd'));


if ($records->count() === 0) {
    $records = collect([[
        'user_id' => null,
        'user_name' => 'No Data',
        'date' => null,
        'status_name' => null,
    ]]);
}
    //return Excel::download(new EmployeeStatusCalendarExport($exportData, $dates), $filename);
    return Excel::download(new MultiMonthEmployeeStatusExport($dataByMonth), $filename);
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

         return [$exportData, $dates];
 }
} 







