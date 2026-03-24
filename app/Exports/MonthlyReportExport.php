<?php

namespace App\Exports;

use App\Models\ViolationRecord;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        public int $year,
    ) {}

    public function array(): array
    {
        $rows = [];
        $grandTotal = 0;

        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::create($this->year, $m, 1)->startOfMonth();
            $end = Carbon::create($this->year, $m, 1)->endOfMonth();

            $base = ViolationRecord::whereBetween('created_at', [$start, $end]);

            $total = (clone $base)->count();
            $grandTotal += $total;

            if ($total === 0) {
                $rows[] = [
                    $start->format('F'),
                    0,
                    '—',
                    '—',
                    '—',
                    '—',
                    '—',
                    'No complaint recorded within this period',
                ];

                continue;
            }

            $rows[] = [
                $start->format('F'),
                $total,
                (clone $base)->where('status', 'Pending Review')->count(),
                (clone $base)->where('status', 'Sanction Active')->count(),
                (clone $base)->where('status', 'Resolved')->count(),
                (clone $base)->where('settled_by', 'Dean')->count(),
                (clone $base)->where('settled_by', 'OSDW')->count(),
                '',
            ];
        }

        if ($grandTotal === 0) {
            $rows = [['No complaint recorded for the year '.$this->year, '', '', '', '', '', '', '']];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Month',
            'Total Cases',
            'Pending Review',
            'Sanction Active',
            'Resolved',
            'Settled by Dean',
            'Settled by OSDW',
            'Remarks',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }

    public function title(): string
    {
        return 'Monthly Report '.$this->year;
    }
}
