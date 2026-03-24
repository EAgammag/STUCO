<?php

namespace App\Livewire\Staff;

use App\Models\User;
use Livewire\Component;

class StudentShow extends Component
{
    public int $studentId;

    public ?array $student = null;

    /**
     * Mount the component with the student ID.
     */
    public function mount(int $studentId): void
    {
        $this->studentId = $studentId;
        $this->loadStudent();
    }

    /**
     * Load the student data.
     */
    protected function loadStudent(): void
    {
        $user = User::role('student')->findOrFail($this->studentId);

        $this->student = [
            'id' => $user->id,
            'student_id' => $user->student_id,
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'college' => $user->college,
            'year_level' => $user->year_level,
            'section' => $user->section,
            'program' => $user->program,
            'created_at' => $user->created_at?->format('F j, Y'),
        ];
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.staff.student-show');
    }
}
