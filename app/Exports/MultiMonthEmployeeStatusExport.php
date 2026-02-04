<?php
namespace App\Exports;


use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class MultiMonthEmployeeStatusExport implements WithMultipleSheets
{
    private array $dataByMonth; // ['2026-01' => [$exportData, $dates], ...]

    public function __construct(array $dataByMonth)
    {
        $this->dataByMonth = $dataByMonth;
    }

    public function sheets(): array
    {

       $months = array_keys($this->dataByMonth);

    // Sort months chronologically (Jan -> Feb -> ...)
    sort($months);

    $sheets = [];

    foreach ($months as $month) {
        [$exportData, $dates] = $this->dataByMonth[$month];

        $title = \Carbon\Carbon::parse($month . '-01')->format('F Y');

        $sheets[] = new EmployeeStatusCalendarExport(
            $exportData,
            $dates,
            $title
        );
    }

    return $sheets;
    }
}