<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class EmployeeStatusCalendarExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(private array $raw, private array $dates) {}

    public function headings(): array
    {
        $head = ['Employee'];
        foreach ($this->dates as $d) {
            $c = Carbon::parse($d);
            $head[] = $c->format('d D'); // e.g., 29 WED
        }
        return $head;
    }

    public function array(): array
    {
        $map = [
            'remote'   => 'Remote',
            'onsite'   => 'On site',
            'me leje' => 'Me leje',
        ];

        $rows = [];
        foreach ($this->raw as $employee) {
            $line = [$employee['employee_name']];
            foreach ($this->dates as $d) {
                $status = trim(strtolower($employee['days'][$d] ?? null));
                $line[] = $status ? ($map[strtolower($status)] ?? strtoupper(substr($status, 0, 1))) : '';
            }
            $rows[] = $line;
        }
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $daysCount    = count($this->dates);
                $lastColIndex = 1 + $daysCount; // A=1
                $lastCol      = Coordinate::stringFromColumnIndex($lastColIndex);
                $lastRow      = count($this->raw) + 1;

                // Basic styles
                $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
                $sheet->getStyle("A1:{$lastCol}{$lastRow}")
                      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                      ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->freezePane('B2');
                $sheet->setAutoFilter("A1:{$lastCol}{$lastRow}");
                $sheet->getRowDimension(1)->setRowHeight(22);
                $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Colors
                $colorR = 'FFCCE5FF'; // Remote
                $colorO = 'FFFFF2CC'; // Onsite
                $colorL = 'FFFFCDD2'; // Me leje
                $weekendShade = 'FFF3F4F6';

                // Shade weekends
                foreach ($this->dates as $i => $d) {
                    $date = Carbon::parse($d);
                    if ($date->isWeekend()) {
                        $col = Coordinate::stringFromColumnIndex(2 + $i);
                        $sheet->getStyle("{$col}1:{$col}{$lastRow}")
                              ->getFill()->setFillType(Fill::FILL_SOLID)
                              ->getStartColor()->setARGB($weekendShade);
                    }
                }

                // Color per value
                for ($r=2; $r <= $lastRow; $r++) {
                    for ($c=2; $c <= $lastColIndex; $c++) {
                        $cell = Coordinate::stringFromColumnIndex($c) . $r;
                        $val  = $sheet->getCell($cell)->getValue();
                        if ($val === 'Remote') {
                            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                                  ->getStartColor()->setARGB($colorR);
                        } elseif ($val === 'On site') {
                            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                                  ->getStartColor()->setARGB($colorO);
                        } elseif ($val === 'Me leje') {
                            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                                  ->getStartColor()->setARGB($colorL);
                        }
                    }
                }

                // Legend
                $legendRow = $lastRow + 2;
                $sheet->fromArray([['Legend', 'Remote', 'Onsite', 'Me leje']], null, "A{$legendRow}");
                $sheet->getStyle("A{$legendRow}:D{$legendRow}")->getFont()->setBold(true);
            }
        ];
    }
}
