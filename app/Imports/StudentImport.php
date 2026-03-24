<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
    public int $importedCount = 0;

    public int $skippedCount = 0;

    /** @var array<int, array{row: int, student_id: string, reason: string}> */
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $studentId = trim((string) ($row['student_id'] ?? ''));

            if (empty($studentId)) {
                $this->skippedCount++;

                continue;
            }

            // Skip duplicate student IDs
            if (User::where('student_id', $studentId)->exists()) {
                $this->skippedCount++;
                $this->errors[] = [
                    'row' => $index + 2, // +2 for header row and 0-index
                    'student_id' => $studentId,
                    'reason' => 'Student ID already exists',
                ];

                continue;
            }

            $firstName = trim((string) ($row['first_name'] ?? ''));
            $lastName = trim((string) ($row['last_name'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));

            // Auto-generate email if not provided
            if (empty($email)) {
                $email = Str::lower($firstName.'.'.$lastName.'.'.$studentId).'@csu.edu.ph';
                $email = preg_replace('/\s+/', '', $email);
            }

            // Skip if email already exists
            if (User::where('email', $email)->exists()) {
                $this->skippedCount++;
                $this->errors[] = [
                    'row' => $index + 2,
                    'student_id' => $studentId,
                    'reason' => 'Email already exists',
                ];

                continue;
            }

            $password = Str::random(10);

            $user = User::create([
                'name' => $firstName.' '.$lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($password),
                'student_id' => $studentId,
                'program' => trim((string) ($row['program'] ?? '')),
                'college' => trim((string) ($row['college'] ?? '')),
                'year_level' => trim((string) ($row['year_level'] ?? '')),
                'section' => trim((string) ($row['section'] ?? '')),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('student');

            $this->importedCount++;
        }
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'program' => 'required|string',
            'college' => 'required|string',
            'year_level' => 'required|string',
            'section' => 'required|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function customValidationMessages(): array
    {
        return [
            'program.required' => 'The program column is required and must be filled in for every row.',
            'college.required' => 'The college column is required and must be filled in for every row.',
            'year_level.required' => 'The year_level column is required and must be filled in for every row.',
            'section.required' => 'The section column is required and must be filled in for every row.',
        ];
    }
}
