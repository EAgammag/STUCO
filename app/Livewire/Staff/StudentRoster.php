<?php

namespace App\Livewire\Staff;

use App\Imports\StudentImport;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StudentRoster extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showImportModal = false;

    public $importFile;

    public ?string $importResult = null;

    public ?string $importError = null;

    /** @var array<int, array{row: int, student_id: string, reason: string}> */
    public array $importErrors = [];

    /**
     * Reset pagination when search input changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Navigate to view student profile.
     */
    public function viewStudent(int $userId): void
    {
        $this->redirect(route('staff.students.show', $userId), navigate: true);
    }

    /**
     * Navigate to create student form.
     */
    public function openCreateForm(): void
    {
        $this->redirect(route('staff.students.create'), navigate: true);
    }

    /**
     * Navigate to edit student form.
     */
    public function openEditForm(int $userId): void
    {
        $this->redirect(route('staff.students.edit', $userId), navigate: true);
    }

    /**
     * Open the import modal.
     */
    public function openImportModal(): void
    {
        $this->reset(['importFile', 'importResult', 'importError', 'importErrors']);
        $this->showImportModal = true;
    }

    /**
     * Close the import modal.
     */
    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->reset(['importFile', 'importResult', 'importError', 'importErrors']);
    }

    /**
     * Import students from uploaded Excel/CSV file.
     */
    public function importStudents(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new StudentImport;
            Excel::import($import, $this->importFile->getRealPath());

            $this->importErrors = $import->errors;

            if ($import->importedCount > 0) {
                $this->importResult = "Successfully imported {$import->importedCount} student(s).";
                if ($import->skippedCount > 0) {
                    $this->importResult .= " Skipped {$import->skippedCount} duplicate(s).";
                }
            } else {
                $this->importResult = "No new students imported. {$import->skippedCount} record(s) were skipped (duplicates or empty).";
            }

            $this->importError = null;
            $this->reset('importFile');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach (array_slice($failures, 0, 5) as $failure) {
                $messages[] = "Row {$failure->row()}: {$failure->attribute()} - ".implode(', ', $failure->errors());
            }
            $this->importError = 'Validation errors: '.implode('; ', $messages);
            $this->importResult = null;
        } catch (\Exception $e) {
            $this->importError = 'Import failed: '.$e->getMessage();
            $this->importResult = null;
        }
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        $students = User::role('student')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('student_id', 'like', '%'.$this->search.'%')
                        ->orWhere('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('name', 'like', '%'.$this->search.'%')
                        ->orWhere('college', 'like', '%'.$this->search.'%')
                        ->orWhere('section', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15);

        return view('livewire.staff.student-roster', compact('students'));
    }
}
