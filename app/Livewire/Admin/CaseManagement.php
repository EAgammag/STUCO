<?php

namespace App\Livewire\Admin;

use App\Exports\ByTypeReportExport;
use App\Exports\CaseSummaryExport;
use App\Exports\MonthlyReportExport;
use App\Models\CaseWorkflowLog;
use App\Models\OffenseRule;
use App\Models\User;
use App\Models\ViolationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CaseManagement extends Component
{
    use WithPagination;

    // Tab
    public string $activeTab = 'cases';

    // Filters
    public $filterStatus = '';

    public $filterInvestigationType = '';

    public $filterOffenseCategory = '';

    public $filterOverdueOnly = false;

    public $searchTerm = '';

    // Sorting
    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    // Modal States
    public $selectedCase;

    public $showAssignSDTModal = false;

    public $showResolveCaseModal = false;

    public $sdtMembers = [];

    public $selectedSDTMembers = [];

    // Resolve Case Fields
    public $settledBy = '';

    public $sanctionImposedDate = '';

    public $sanctionImposedTime = '';

    public $sanctionEffectiveDate = '';

    public $sanctionEffectiveTime = '';

    public $actionTaken = '';

    // Report Filters
    public string $reportPeriodType = 'monthly';

    public string $reportMonth = '';

    public string $reportQuarter = '';

    public string $reportSemester = '';

    public string $reportYear = '';

    public string $reportStartDate = '';

    public string $reportEndDate = '';

    protected $queryString = [
        'activeTab' => ['except' => 'cases'],
        'filterStatus' => ['except' => ''],
        'filterInvestigationType' => ['except' => ''],
        'searchTerm' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->reportYear = (string) now()->year;
        $this->reportMonth = now()->format('m');
        $this->reportQuarter = (string) ceil(now()->month / 3);
        $this->reportSemester = now()->month <= 5 ? '2nd' : (now()->month <= 10 ? '1st' : 'summer');
        $this->loadSDTMembers();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function loadSDTMembers(): void
    {
        $this->sdtMembers = User::role(['staff', 'administrator'])
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->getRoleNames()->first(),
            ]);
    }

    public function updatingSearchTerm(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['filterStatus', 'filterInvestigationType', 'filterOffenseCategory', 'filterOverdueOnly', 'searchTerm']);
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewCase($caseId): void
    {
        $case = ViolationRecord::with(['student', 'offenseRule', 'reporter', 'evidence', 'workflowLogs.actor'])
            ->findOrFail($caseId);

        $case->logAccess(Auth::id(), 'OSDW Case Review');

        $this->selectedCase = $case;
        $this->dispatch('case-loaded');
    }

    public function closeCase(): void
    {
        $this->selectedCase = null;
    }

    public function openAssignSDTModal($caseId): void
    {
        $this->selectedCase = ViolationRecord::findOrFail($caseId);
        $this->selectedSDTMembers = $this->selectedCase->sdt_members ?? [];
        $this->showAssignSDTModal = true;
    }

    public function assignSDT(): void
    {
        $this->validate([
            'selectedSDTMembers' => 'required|array|min:5|max:5',
        ], [
            'selectedSDTMembers.min' => 'You must select exactly 5 tribunal members per CSU Section G.1',
            'selectedSDTMembers.max' => 'You must select exactly 5 tribunal members per CSU Section G.1',
        ]);

        $this->selectedCase->update([
            'assigned_to_sdt' => true,
            'sdt_members' => $this->selectedSDTMembers,
            'status' => 'Pending Review',
        ]);

        CaseWorkflowLog::logAction(
            $this->selectedCase->id,
            Auth::id(),
            'Hearing Scheduled',
            'Student Disciplinary Tribunal assigned (5 members)',
            ['sdt_member_ids' => $this->selectedSDTMembers]
        );

        session()->flash('success', 'Student Disciplinary Tribunal successfully assigned to Case #'.$this->selectedCase->case_tracking_number);

        $this->showAssignSDTModal = false;
        $this->reset('selectedSDTMembers');
    }

    public function scheduleHearing($caseId, $hearingDate): void
    {
        $case = ViolationRecord::findOrFail($caseId);

        $case->update([
            'hearing_scheduled_date' => $hearingDate,
            'decision_deadline' => Carbon::parse($hearingDate)->addWeekdays(15),
        ]);

        CaseWorkflowLog::logAction(
            $case->id,
            Auth::id(),
            'Hearing Scheduled',
            "Hearing scheduled for {$hearingDate}",
            ['hearing_date' => $hearingDate]
        );

        session()->flash('success', 'Hearing scheduled for Case #'.$case->case_tracking_number);
    }

    public function openResolveCaseModal($caseId): void
    {
        $this->selectedCase = ViolationRecord::findOrFail($caseId);
        $this->reset(['settledBy', 'sanctionImposedDate', 'sanctionImposedTime', 'sanctionEffectiveDate', 'sanctionEffectiveTime', 'actionTaken']);
        $this->showResolveCaseModal = true;
    }

    public function resolveCase(): void
    {
        $this->validate([
            'settledBy' => 'required|in:Dean,OSDW',
            'sanctionImposedDate' => 'required|date',
            'sanctionImposedTime' => 'required',
            'actionTaken' => 'required|string|min:5',
            'sanctionEffectiveDate' => 'nullable|date',
            'sanctionEffectiveTime' => 'nullable',
        ]);

        $sanctionImposedAt = Carbon::parse($this->sanctionImposedDate.' '.$this->sanctionImposedTime);
        $sanctionEffectiveAt = $this->sanctionEffectiveDate
            ? Carbon::parse($this->sanctionEffectiveDate.' '.($this->sanctionEffectiveTime ?: '00:00'))
            : null;

        $this->selectedCase->update([
            'sanction_imposed_at' => $sanctionImposedAt,
            'sanction_effective_at' => $sanctionEffectiveAt,
            'settled_by' => $this->settledBy,
            'action_taken' => $this->actionTaken,
            'resolution_date' => now(),
            'status' => 'Sanction Active',
            'decided_by' => Auth::id(),
        ]);

        CaseWorkflowLog::logAction(
            $this->selectedCase->id,
            Auth::id(),
            'Sanction Applied',
            "Case settled by {$this->settledBy}. Action: {$this->actionTaken}",
            [
                'settled_by' => $this->settledBy,
                'sanction_imposed_at' => $sanctionImposedAt->toISOString(),
                'sanction_effective_at' => $sanctionEffectiveAt?->toISOString(),
                'action_taken' => $this->actionTaken,
            ]
        );

        session()->flash('success', 'Sanction applied to Case #'.$this->selectedCase->case_tracking_number);

        $this->showResolveCaseModal = false;
        $this->selectedCase = null;
    }

    /**
     * Get the date range based on the report period selector.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public function getReportDateRange(): array
    {
        $year = (int) ($this->reportYear ?: now()->year);

        return match ($this->reportPeriodType) {
            'monthly' => [
                Carbon::create($year, (int) $this->reportMonth, 1)->startOfMonth(),
                Carbon::create($year, (int) $this->reportMonth, 1)->endOfMonth(),
            ],
            'quarterly' => [
                Carbon::create($year, (((int) $this->reportQuarter - 1) * 3) + 1, 1)->startOfMonth(),
                Carbon::create($year, ((int) $this->reportQuarter) * 3, 1)->endOfMonth(),
            ],
            'semestral' => match ($this->reportSemester) {
                '1st' => [Carbon::create($year, 6, 1)->startOfMonth(), Carbon::create($year, 10, 31)->endOfDay()],
                '2nd' => [Carbon::create($year, 11, 1)->startOfMonth(), Carbon::create($year + 1, 3, 31)->endOfDay()],
                'summer' => [Carbon::create($year, 4, 1)->startOfMonth(), Carbon::create($year, 5, 31)->endOfDay()],
                default => [Carbon::create($year, 1, 1)->startOfYear(), Carbon::create($year, 12, 31)->endOfYear()],
            },
            'custom' => [
                $this->reportStartDate ? Carbon::parse($this->reportStartDate)->startOfDay() : now()->startOfYear(),
                $this->reportEndDate ? Carbon::parse($this->reportEndDate)->endOfDay() : now()->endOfDay(),
            ],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Get a human-readable period label.
     */
    public function getReportPeriodLabel(): string
    {
        [$start, $end] = $this->getReportDateRange();

        return match ($this->reportPeriodType) {
            'monthly' => $start->format('F Y'),
            'quarterly' => 'Q'.$this->reportQuarter.' '.$this->reportYear,
            'semestral' => ucfirst($this->reportSemester).' Semester '.($this->reportSemester === '2nd' ? $this->reportYear.'-'.($this->reportYear + 1) : $this->reportYear),
            'custom' => $start->format('M d, Y').' — '.$end->format('M d, Y'),
            default => $start->format('F Y'),
        };
    }

    public function getCaseSummaryDataProperty(): array
    {
        [$start, $end] = $this->getReportDateRange();

        $records = ViolationRecord::with(['student', 'offenseRule', 'reporter', 'decisionMaker'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'records' => $records,
            'period' => $this->getReportPeriodLabel(),
            'start' => $start,
            'end' => $end,
            'hasRecords' => $records->isNotEmpty(),
        ];
    }

    public function getMonthlyReportDataProperty(): array
    {
        $year = (int) ($this->reportYear ?: now()->year);
        $months = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::create($year, $m, 1)->startOfMonth();
            $end = Carbon::create($year, $m, 1)->endOfMonth();

            $records = ViolationRecord::whereBetween('created_at', [$start, $end]);

            $months[] = [
                'month' => $start->format('F'),
                'month_num' => $m,
                'total' => (clone $records)->count(),
                'pending' => (clone $records)->where('status', 'Pending Review')->count(),
                'active' => (clone $records)->where('status', 'Sanction Active')->count(),
                'resolved' => (clone $records)->where('status', 'Resolved')->count(),
                'by_dean' => (clone $records)->where('settled_by', 'Dean')->count(),
                'by_osdw' => (clone $records)->where('settled_by', 'OSDW')->count(),
            ];
        }

        $hasAnyRecords = collect($months)->sum('total') > 0;

        return [
            'months' => $months,
            'year' => $year,
            'hasRecords' => $hasAnyRecords,
        ];
    }

    public function getByTypeReportDataProperty(): array
    {
        [$start, $end] = $this->getReportDateRange();

        $categories = OffenseRule::select('category')->distinct()->pluck('category');
        $data = [];

        foreach ($categories as $category) {
            $records = ViolationRecord::whereBetween('created_at', [$start, $end])
                ->whereHas('offenseRule', fn ($q) => $q->where('category', $category));

            $data[] = [
                'category' => $category,
                'total' => (clone $records)->count(),
                'pending' => (clone $records)->where('status', 'Pending Review')->count(),
                'active' => (clone $records)->where('status', 'Sanction Active')->count(),
                'resolved' => (clone $records)->where('status', 'Resolved')->count(),
                'by_dean' => (clone $records)->where('settled_by', 'Dean')->count(),
                'by_osdw' => (clone $records)->where('settled_by', 'OSDW')->count(),
            ];
        }

        $hasRecords = collect($data)->sum('total') > 0;

        return [
            'categories' => $data,
            'period' => $this->getReportPeriodLabel(),
            'hasRecords' => $hasRecords,
        ];
    }

    public function exportCaseSummary()
    {
        [$start, $end] = $this->getReportDateRange();

        return Excel::download(
            new CaseSummaryExport($start, $end),
            'case-summary-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function exportMonthlyReport()
    {
        $year = (int) ($this->reportYear ?: now()->year);

        return Excel::download(
            new MonthlyReportExport($year),
            'monthly-report-'.$year.'.xlsx'
        );
    }

    public function exportByTypeReport()
    {
        [$start, $end] = $this->getReportDateRange();

        return Excel::download(
            new ByTypeReportExport($start, $end),
            'by-type-report-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function getCasesProperty()
    {
        $query = ViolationRecord::with(['student', 'offenseRule', 'reporter'])
            ->when($this->searchTerm, function ($q) {
                $q->where('case_tracking_number', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('student', fn ($sq) => $sq->where('name', 'like', "%{$this->searchTerm}%"))
                    ->orWhereHas('offenseRule', fn ($oq) => $oq->where('title', 'like', "%{$this->searchTerm}%"));
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterInvestigationType, fn ($q) => $q->where('investigation_type', $this->filterInvestigationType))
            ->when($this->filterOffenseCategory, function ($q) {
                $q->whereHas('offenseRule', fn ($oq) => $oq->where('category', $this->filterOffenseCategory));
            })
            ->when($this->filterOverdueOnly, function ($q) {
                $q->where(function ($sq) {
                    $sq->whereNotNull('answer_deadline')
                        ->where('answer_deadline', '<', now())
                        ->whereNull('student_answer_submitted_date')
                        ->orWhere(function ($dsq) {
                            $dsq->whereNotNull('decision_deadline')
                                ->where('decision_deadline', '<', now())
                                ->whereNull('final_decision');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate(20);
    }

    public function getStatisticsProperty()
    {
        return [
            'total_cases' => ViolationRecord::count(),
            'pending_review' => ViolationRecord::where('status', 'Pending Review')->count(),
            'active_sanctions' => ViolationRecord::where('status', 'Sanction Active')->count(),
            'resolved' => ViolationRecord::where('status', 'Resolved')->count(),
            'overdue_answer' => ViolationRecord::whereNotNull('answer_deadline')
                ->where('answer_deadline', '<', now())
                ->whereNull('student_answer_submitted_date')
                ->count(),
            'overdue_decision' => ViolationRecord::whereNotNull('decision_deadline')
                ->where('decision_deadline', '<', now())
                ->whereNull('final_decision')
                ->count(),
            'awaiting_tribunal' => ViolationRecord::where('investigation_type', 'Tribunal')
                ->where('assigned_to_sdt', false)
                ->count(),
        ];
    }

    public function render()
    {
        $viewData = [
            'cases' => $this->cases,
            'statistics' => $this->statistics,
            'offenseCategories' => OffenseRule::select('category')->distinct()->pluck('category'),
        ];

        if ($this->activeTab === 'case-summary') {
            $viewData['caseSummary'] = $this->caseSummaryData;
        } elseif ($this->activeTab === 'monthly-report') {
            $viewData['monthlyReport'] = $this->monthlyReportData;
        } elseif ($this->activeTab === 'by-type-report') {
            $viewData['byTypeReport'] = $this->byTypeReportData;
        }

        return view('livewire.admin.case-management', $viewData)
            ->layout('layouts.admin', ['title' => 'Case Management']);
    }
}
