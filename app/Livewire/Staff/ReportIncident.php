<?php

namespace App\Livewire\Staff;

use App\Models\CaseEvidence;
use App\Models\CaseWorkflowLog;
use App\Models\OffenseRule;
use App\Models\User;
use App\Models\ViolationRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReportIncident extends Component
{
    use WithFileUploads;

    // Form Fields
    public $student_id;

    public $student_id_search;

    public $offense_category = '';

    public $offense_id = '';

    public $date_of_incident;

    public $incident_description = '';

    public $evidence_files = [];

    public $evidence_descriptions = [];

    // Computed Data
    public $selectedStudent;

    public $offenseCategories = [];

    public $filteredOffenses = [];

    public $selectedOffense;

    protected $rules = [
        'student_id' => 'required|exists:users,id',
        'offense_id' => 'required|exists:offense_rules,id',
        'date_of_incident' => 'required|date|before_or_equal:today',
        'incident_description' => 'required|string|min:20|max:5000',
        'evidence_files.*' => 'nullable|file|max:10240', // Max 10MB per file
    ];

    protected $messages = [
        'incident_description.min' => 'Please provide a detailed incident description (at least 20 characters).',
        'date_of_incident.before_or_equal' => 'Incident date cannot be in the future.',
    ];

    public function mount(): void
    {
        $this->date_of_incident = now()->format('Y-m-d');
        $this->loadOffenseCategories();
    }

    public function loadOffenseCategories(): void
    {
        $this->offenseCategories = OffenseRule::active()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    public function updatedOffenseCategory(): void
    {
        $this->offense_id = '';
        $this->selectedOffense = null;

        if ($this->offense_category) {
            $this->filteredOffenses = OffenseRule::active()
                ->where('category', $this->offense_category)
                ->orderBy('title')
                ->get();
        } else {
            $this->filteredOffenses = [];
        }
    }

    public function updatedOffenseId(): void
    {
        if ($this->offense_id) {
            $this->selectedOffense = OffenseRule::find($this->offense_id);
        } else {
            $this->selectedOffense = null;
        }
    }

    public function searchStudent(): void
    {
        $this->validate([
            'student_id_search' => 'required|string',
        ]);

        $student = User::role('Student')
            ->where(function ($query) {
                $query->where('student_id', $this->student_id_search)
                    ->orWhere('email', $this->student_id_search);
            })
            ->first();

        if ($student) {
            $this->student_id = $student->id;
            $this->selectedStudent = $student;
            $this->dispatch('student-found');
        } else {
            $this->selectedStudent = null;
            $this->addError('student_id_search', 'Student not found. Please verify the Student ID or email.');
        }
    }

    public function clearStudent(): void
    {
        $this->student_id = null;
        $this->student_id_search = '';
        $this->selectedStudent = null;
    }

    public function removeEvidence($index): void
    {
        unset($this->evidence_files[$index]);
        unset($this->evidence_descriptions[$index]);

        $this->evidence_files = array_values($this->evidence_files);
        $this->evidence_descriptions = array_values($this->evidence_descriptions);
    }

    public function submitReport(): void
    {
        $this->validate();

        // Calculate offense count for progressive sanctions
        $offenseCount = $this->calculateOffenseCount();

        // Generate case tracking number
        $caseTrackingNumber = ViolationRecord::generateCaseTrackingNumber();

        // Get the offense rule to determine sanction
        $offenseRule = OffenseRule::find($this->offense_id);
        $appliedSanction = $offenseRule->getSanctionForOffenseCount($offenseCount);

        // Determine investigation type based on offense
        $investigationType = $offenseRule->requiresTribunal() ? 'Tribunal' : 'Summary';

        // Create violation record
        $violationRecord = ViolationRecord::create([
            'case_tracking_number' => $caseTrackingNumber,
            'student_id' => $this->student_id,
            'offense_id' => $this->offense_id,
            'offense_count' => $offenseCount,
            'applied_sanction' => $appliedSanction,
            'reported_by' => Auth::id(),
            'status' => 'Pending Review',
            'investigation_type' => $investigationType,
            'incident_description' => $this->incident_description,
            'date_of_incident' => $this->date_of_incident,
            'charge_filed_date' => now(),
        ]);

        // Upload evidence files
        foreach ($this->evidence_files as $index => $file) {
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('case-evidence', 'private');

            CaseEvidence::create([
                'violation_record_id' => $violationRecord->id,
                'uploaded_by' => Auth::id(),
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $this->determineFileType($file),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $this->evidence_descriptions[$index] ?? null,
                'evidence_type' => $this->determineEvidenceType($file),
            ]);
        }

        // Log the action
        CaseWorkflowLog::logAction(
            $violationRecord->id,
            Auth::id(),
            'Case Created',
            "Incident reported: {$offenseRule->title}",
            [
                'offense_count' => $offenseCount,
                'investigation_type' => $investigationType,
                'evidence_count' => count($this->evidence_files),
            ]
        );

        // Success notification
        session()->flash('success', "Incident reported successfully. Case Tracking #: {$caseTrackingNumber}");

        // Reset form
        $this->reset(['student_id', 'student_id_search', 'offense_category', 'offense_id',
            'incident_description', 'evidence_files', 'evidence_descriptions']);
        $this->selectedStudent = null;
        $this->selectedOffense = null;
        $this->date_of_incident = now()->format('Y-m-d');

        $this->dispatch('incident-reported');
    }

    private function calculateOffenseCount(): int
    {
        return ViolationRecord::where('student_id', $this->student_id)
            ->where('offense_id', $this->offense_id)
            ->whereIn('status', ['Pending Review', 'Sanction Active', 'Resolved'])
            ->count() + 1;
    }

    private function determineFileType($file): string
    {
        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mime, 'audio/')) {
            return 'audio';
        }

        return 'document';
    }

    private function determineEvidenceType($file): string
    {
        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image/')) {
            return 'Photo Evidence';
        }
        if (str_starts_with($mime, 'video/')) {
            return 'Video Evidence';
        }
        if (str_starts_with($mime, 'audio/')) {
            return 'Audio Recording';
        }
        if (str_contains($mime, 'pdf') || str_contains($mime, 'word')) {
            return 'Document';
        }

        return 'Other';
    }

    public function render()
    {
        return view('livewire.staff.report-incident');
    }
}
