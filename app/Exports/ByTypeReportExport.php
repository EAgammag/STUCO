<?php

namespace App\Exports;

use App\Models\OffenseRule;
use App\Models\ViolationRecord;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ByTypeReportExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        public Carbon $start,
        public Carbon $end,
    ) {}

    public function array(): array
    {
        $categories = OffenseRule::select('category')->distinct()->pluck('category');
        $rows = [];
        $grandTotal = 0;

        foreach ($categories as $category) {
            $base = ViolationRecord::whereBetween('created_at', [$this->start, $this->end])
                ->whereHas('offenseRule', fn ($q) => $q->where('category', $category));

            $total = (clone $base)->count();
            $grandTotal += $total;

            $rows[] = [
                $category,
                $total,
                (clone $base)->where('status', 'Pending Review')->count(),
                (clone $base)->where('status', 'Sanction Active')->count(),
                (clone $base)->where('status', 'Resolved')->count(),
                (clone $base)->where('settled_by', 'Dean')->count(),
                (clone $base)->where('settled_by', 'OSDW')->count(),
            ];
        }

        if ($grandTotal === 0) {
            $rows = [['No complaint recorded within the period ('.$this->start->format('M d, Y').' - '.$this->end->format('M d, Y').')', '', '', '', '', '', '']];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Offense Category',
            'Total Cases',
            'Pending Review',
            'Sanction Active',
            'Resolved',
            'Settled by Dean',
            'Settled by OSDW',
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
        return 'By Type Report';
    }
}
