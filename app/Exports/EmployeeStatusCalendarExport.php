<?php
// app/Exports/EmployeeStatusCalendarExport.php
namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EmployeeStatusCalendarExport implements WithEvents
{
     /** 
     * KEEPING THE SAME CONSTRUCTOR SIGNATURE YOU ALREADY USE:
     *   new EmployeeStatusCalendarExport($exportData, $dates)
     *
     * $raw format (as your current service builds it):
     * [
     *   ['employee_id'=>2, 'employee_name'=>'Erisa', 'days'=>['YYYY-MM-DD'=>'remote'|null, ...]],
     *   ...
     * ]
     * $dates: ['YYYY-MM-DD', ...] continuous range for the month
     */
     public function __construct(private array $raw, private array $dates) {}

    // Tweak if you want more/less event rows per day
     private int $maxEventsPerDay = 5;

            public function registerEvents(): array
            {
                return [
                AfterSheet::class => function(AfterSheet $e) {
                $sheet = $e->sheet->getDelegate();
                $this->renderMonthGrid($sheet);
                }
                ];
            }

            private function renderMonthGrid(Worksheet $sheet): void
            {
                $sheet->getParent()->getDefaultStyle()->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFFFFF');
                if (empty($this->dates)) {
                $sheet->setCellValue('A1', 'No dates provided'); return;
                
            }

       // ===== Derive month/year from the first date and build date->events =====
        $firstDate = Carbon::parse($this->dates[0]);
        $year  = (int)$firstDate->year;
        $month = (int)$firstDate->month;
        $daysInMonth= Carbon::create($year, $month, 1)->daysInMonth;
        $firstDow  = (int)Carbon::create($year, $month, 1)->dayOfWeek; // 0=Sun..6=Sat

    // eventsByDate['YYYY-MM-DD'] = [ ['name'=>'Enriketa','status'=>'onsite','updated_at'=>null], ... ]
        $eventsByDate = [];
        foreach ($this->raw as $emp) {
            $name = $emp['employee_name'] ?? ('User '.($emp['employee_id'] ?? ''));
        foreach ($this->dates as $d) {
        $status = $emp['days'][$d] ?? null;
            if ($status) {
            $eventsByDate[$d][] = ['name'=>$name, 'status'=>strtolower($status), 'updated_at'=>null];
            }
        }
        }

      // ===== Visual layout =====
        $titleRow  = 1; // big title
        $dowRow = 2; // SUNDAY..SATURDAY
        $startRow  = 3; // calendar blocks start here
        $numCols = 7; // Sun..Sat
        $blockRows = 1 + $this->maxEventsPerDay; // date-number row + N event rows

        // Column widths
        for ($c=1; $c<= $numCols; $c++) {
        $sheet->getColumnDimensionByColumn($c)->setWidth(20);
        }

       // Title (e.g., "OCTOBER 2025")
        $sheet->mergeCellsByColumnAndRow(1, $titleRow, $numCols, $titleRow);
        $sheet->setCellValueByColumnAndRow(1, $titleRow, strtoupper(Carbon::create($year, $month, 1)->format('F Y')));
        $sheet->getStyleByColumnAndRow(1, $titleRow, $numCols, $titleRow)->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle("A{$titleRow}:G{$titleRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Weekday labels
        $dowLabels = ['SUNDAY','MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY'];
        foreach ($dowLabels as $i => $lbl) {
        $sheet->setCellValueByColumnAndRow($i+1, $dowRow, $lbl);
        $dowHeaderBg = 'FF92CDDC'; // soft blue
        $sheet->getStyle("A{$dowRow}:G{$dowRow}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($dowHeaderBg);
        $sheet->getStyle("A{$dowRow}:G{$dowRow}")->getFont()->setBold(true);
        }
        $sheet->getStyleByColumnAndRow(1, $dowRow, $numCols, $dowRow)->getFont()->setBold(true);
        $sheet->getRowDimension($dowRow)->setRowHeight(22);
        $sheet->getStyle("A{$dowRow}:G{$dowRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

 // Draw the calendar (5–6 weeks)
        $currentRow = $startRow;
        $day = 1;
        for ($week = 0; $week < 6; $week++) {
        // give each internal row a nice height
        for ($r = 0; $r < $blockRows; $r++) {
            $sheet->getRowDimension($currentRow + $r)->setRowHeight(18);
        }

        for ($col = 1; $col <= 7; $col++) {
        $top = $currentRow;
        $bottom = $currentRow + $blockRows - 1;
        $dateHeaderBg = 'FFDAEEF3'; // very light gray/blue
        $sheet->getStyleByColumnAndRow($col, $top)->getFont()->setBold(true);
        $sheet->getStyleByColumnAndRow($col, $top)->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()->setARGB($dateHeaderBg);

        // outline border for day box
        $sheet->getStyleByColumnAndRow($col, $top, $col, $bottom)
        ->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFB0B0B0');

        $isFirstWeek = ($week === 0);
        $shouldPlace = ($isFirstWeek ? ($col-1) >= $firstDow : true) && ($day <= $daysInMonth);

        if ($shouldPlace) {
        // day number (top-right)
        $sheet->setCellValueByColumnAndRow($col, $top, $day);
        $sheet->getStyleByColumnAndRow($col, $top)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $dateKey = Carbon::create($year, $month, $day)->toDateString();
        $events = $eventsByDate[$dateKey] ?? [];

        // fill up to N events
        $slots = min($this->maxEventsPerDay, count($events));
        for ($i = 0; $i < $slots; $i++) {
        $row = $top + 1 + $i;
        $text = $events[$i]['name'].' — '.ucfirst($events[$i]['status']);
        $sheet->setCellValueByColumnAndRow($col, $row, $text);
        $sheet->getStyleByColumnAndRow($col, $row)->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_LEFT)
        ->setVertical(Alignment::VERTICAL_CENTER)
        ->setWrapText(true)
        ->setIndent(1);

        $sheet->getStyleByColumnAndRow($col, $row)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB($this->statusColor($events[$i]['status']));
        }

        $day++;
    }
    }

        $currentRow += $blockRows;
        if ($day > $daysInMonth) break;
        }
        }

        private function statusColor(string $status): string
        {
        // Adjust to your palette (ARGB)
            return match (strtolower($status)) {
            'remote'  => 'FFCCE5FF', // light blue
            'onsite'  => 'FFFFF2CC', // light yellow
            'me leje' => 'FFFFCDD2', // light red
            default => 'FFF3F4F6', // neutral gray
            };
            }
        }