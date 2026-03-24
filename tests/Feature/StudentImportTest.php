<?php

namespace Tests\Feature;

use App\Imports\StudentImport;
use App\Livewire\Staff\StudentRoster;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentImportTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'administrator']);

        $this->staff = User::factory()->create();
        $this->staff->assignRole('staff');
    }

    public function test_student_roster_shows_import_button(): void
    {
        Livewire::actingAs($this->staff)
            ->test(StudentRoster::class)
            ->assertSee('Import from SIA');
    }

    public function test_import_modal_opens_and_closes(): void
    {
        Livewire::actingAs($this->staff)
            ->test(StudentRoster::class)
            ->call('openImportModal')
            ->assertSet('showImportModal', true)
            ->call('closeImportModal')
            ->assertSet('showImportModal', false);
    }

    public function test_import_validates_file_required(): void
    {
        Livewire::actingAs($this->staff)
            ->test(StudentRoster::class)
            ->call('openImportModal')
            ->call('importStudents')
            ->assertHasErrors(['importFile']);
    }

    public function test_student_import_creates_users_from_collection(): void
    {
        $import = new StudentImport;

        $rows = collect([
            collect([
                'student_id' => '2025-0001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'juan.delacruz@csu.edu.ph',
                'program' => 'BSIT',
                'college' => 'COLLEGE OF INFORMATION AND COMPUTING SCIENCES',
                'year_level' => '2nd Year',
                'section' => 'A',
            ]),
            collect([
                'student_id' => '2025-0002',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria.santos@csu.edu.ph',
                'program' => 'BSCS',
                'college' => 'COLLEGE OF INFORMATION AND COMPUTING SCIENCES',
                'year_level' => '1st Year',
                'section' => 'B',
            ]),
        ]);

        $import->collection($rows);

        $this->assertEquals(2, $import->importedCount);
        $this->assertEquals(0, $import->skippedCount);

        $this->assertDatabaseHas('users', [
            'student_id' => '2025-0001',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
        ]);

        $this->assertDatabaseHas('users', [
            'student_id' => '2025-0002',
            'first_name' => 'Maria',
        ]);

        // Verify student role assigned
        $user = User::where('student_id', '2025-0001')->first();
        $this->assertTrue($user->hasRole('student'));
    }

    public function test_student_import_skips_duplicate_student_id(): void
    {
        User::factory()->student()->create(['student_id' => '2025-0001']);

        $import = new StudentImport;

        $rows = collect([
            collect([
                'student_id' => '2025-0001',
                'first_name' => 'Duplicate',
                'last_name' => 'Student',
                'email' => 'dup@csu.edu.ph',
                'program' => 'BSIT',
                'college' => 'Test College',
                'year_level' => '1st Year',
                'section' => 'A',
            ]),
        ]);

        $import->collection($rows);

        $this->assertEquals(0, $import->importedCount);
        $this->assertEquals(1, $import->skippedCount);
        $this->assertCount(1, $import->errors);
        $this->assertEquals('Student ID already exists', $import->errors[0]['reason']);
    }

    public function test_student_import_auto_generates_email_when_empty(): void
    {
        $import = new StudentImport;

        $rows = collect([
            collect([
                'student_id' => '2025-0099',
                'first_name' => 'Pedro',
                'last_name' => 'Garcia',
                'email' => '',
                'program' => 'BSBA',
                'college' => 'COBEA',
                'year_level' => '3rd Year',
                'section' => 'C',
            ]),
        ]);

        $import->collection($rows);

        $this->assertEquals(1, $import->importedCount);

        $user = User::where('student_id', '2025-0099')->first();
        $this->assertNotNull($user);
        $this->assertStringContainsString('pedro', strtolower($user->email));
        $this->assertStringContainsString('@csu.edu.ph', $user->email);
    }

    public function test_student_import_skips_empty_student_id_rows(): void
    {
        $import = new StudentImport;

        $rows = collect([
            collect([
                'student_id' => '',
                'first_name' => 'Empty',
                'last_name' => 'Row',
                'email' => 'empty@test.com',
                'program' => '',
                'college' => '',
                'year_level' => '',
                'section' => '',
            ]),
        ]);

        $import->collection($rows);

        $this->assertEquals(0, $import->importedCount);
        $this->assertEquals(1, $import->skippedCount);
    }
}
