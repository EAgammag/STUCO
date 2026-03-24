<?php

namespace App\Exports;

use App\Models\ViolationRecord;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CaseSummaryExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        public Carbon $start,
        public Carbon $end,
    ) {}

    public function collection()
    {
        $records = ViolationRecord::with(['student', 'offenseRule', 'reporter', 'decisionMaker'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($records->isEmpty()) {
            return collect([['no_complaint' => true]]);
        }

        return $records;
    }

    public function headings(): array
    {
        return [
            'Case #',
            'Student Name',
            'Student ID',
            'College',
            'Offense',
            'Category',
            'Status',
            'Investigation Type',
            'Settled By',
            'Action Taken',
            'Sanction Imposed (Date & Time)',
            'Sanction Effective (Date & Time)',
            'Applied Sanction',
            'Reported By',
            'Date Filed',
            'Resolution Date',
        ];
    }

    /**
     * @param  ViolationRecord|array  $row
     */
    public function map($row): array
    {
        if (is_array($row) && ($row['no_complaint'] ?? false)) {
            return ['No complaint recorded within the period ('.$this->start->format('M d, Y').' - '.$this->end->format('M d, Y').')'];
        }

        return [
            $row->case_tracking_number,
            $row->student?->name ?? 'N/A',
            $row->student?->student_id ?? 'N/A',
            $row->student?->college ?? 'N/A',
            $row->offenseRule?->title ?? 'N/A',
            $row->offenseRule?->category ?? 'N/A',
            $row->status,
            $row->investigation_type,
            $row->settled_by ?? '—',
            $row->action_taken ?? '—',
            $row->sanction_imposed_at?->format('M d, Y g:i A') ?? '—',
            $row->sanction_effective_at?->format('M d, Y g:i A') ?? '—',
            $row->applied_sanction ?? '—',
            $row->reporter?->name ?? 'N/A',
            $row->charge_filed_date?->format('M d, Y') ?? $row->created_at->format('M d, Y'),
            $row->resolution_date?->format('M d, Y') ?? '—',
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
        return 'Case Summary';
    }
}
