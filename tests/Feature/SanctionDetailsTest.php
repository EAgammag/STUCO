<?php

namespace Tests\Feature;

use App\Livewire\Admin\CaseManagement;
use App\Models\OffenseRule;
use App\Models\User;
use App\Models\ViolationRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SanctionDetailsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $student;

    private User $staff;

    private OffenseRule $offense;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'administrator']);
        Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'student']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('administrator');

        $this->staff = User::factory()->create();
        $this->staff->assignRole('staff');

        $this->student = User::factory()->student()->create();
        $this->student->assignRole('student');

        $this->offense = OffenseRule::factory()->create();
    }

    public function test_violation_record_stores_sanction_details(): void
    {
        $record = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'status' => 'Sanction Active',
            'settled_by' => 'Dean',
            'sanction_imposed_at' => '2025-06-15 10:30:00',
            'sanction_effective_at' => '2025-06-20 08:00:00',
            'action_taken' => 'Community service for 40 hours',
        ]);

        $this->assertDatabaseHas('violation_records', [
            'id' => $record->id,
            'settled_by' => 'Dean',
            'action_taken' => 'Community service for 40 hours',
        ]);

        $record->refresh();
        $this->assertEquals('Dean', $record->settled_by);
        $this->assertNotNull($record->sanction_imposed_at);
        $this->assertNotNull($record->sanction_effective_at);
        $this->assertEquals('Community service for 40 hours', $record->action_taken);
    }

    public function test_violation_record_settled_by_label(): void
    {
        $recordDean = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'settled_by' => 'Dean',
        ]);

        $recordOsdw = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'settled_by' => 'OSDW',
        ]);

        $this->assertEquals('Settled by the Dean', $recordDean->settledByLabel());
        $this->assertEquals('Settled by OSDW', $recordOsdw->settledByLabel());
    }

    public function test_resolve_case_sets_sanction_details(): void
    {
        $case = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'status' => 'Pending Review',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseManagement::class)
            ->call('openResolveCaseModal', $case->id)
            ->set('settledBy', 'OSDW')
            ->set('sanctionImposedDate', '2025-06-15')
            ->set('sanctionImposedTime', '10:30')
            ->set('sanctionEffectiveDate', '2025-06-20')
            ->set('sanctionEffectiveTime', '08:00')
            ->set('actionTaken', 'Suspension for 5 school days')
            ->call('resolveCase')
            ->assertHasNoErrors();

        $case->refresh();
        $this->assertEquals('Sanction Active', $case->status);
        $this->assertEquals('OSDW', $case->settled_by);
        $this->assertEquals('Suspension for 5 school days', $case->action_taken);
        $this->assertNotNull($case->sanction_imposed_at);
    }

    public function test_resolve_case_requires_settled_by(): void
    {
        $case = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'status' => 'Pending Review',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseManagement::class)
            ->call('openResolveCaseModal', $case->id)
            ->set('settledBy', '')
            ->set('sanctionImposedDate', '2025-06-15')
            ->set('sanctionImposedTime', '10:30')
            ->set('actionTaken', 'Test action')
            ->call('resolveCase')
            ->assertHasErrors(['settledBy']);
    }

    public function test_resolve_case_requires_action_taken(): void
    {
        $case = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'status' => 'Pending Review',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CaseManagement::class)
            ->call('openResolveCaseModal', $case->id)
            ->set('settledBy', 'Dean')
            ->set('sanctionImposedDate', '2025-06-15')
            ->set('sanctionImposedTime', '10:30')
            ->set('actionTaken', '')
            ->call('resolveCase')
            ->assertHasErrors(['actionTaken']);
    }

    public function test_staff_my_reports_shows_case_dispositions(): void
    {
        $case = ViolationRecord::factory()->create([
            'student_id' => $this->student->id,
            'offense_id' => $this->offense->id,
            'reported_by' => $this->staff->id,
            'status' => 'Sanction Active',
            'settled_by' => 'Dean',
            'sanction_imposed_at' => '2025-06-15 10:30:00',
            'action_taken' => 'Written warning issued',
        ]);

        $response = $this->actingAs($this->staff)->get(route('staff.my-reports'));

        $response->assertStatus(200);
        $response->assertSee('Case Dispositions');
        $response->assertSee($case->case_tracking_number);
        $response->assertSee('Dean');
        $response->assertSee('Written warning issued');
    }
}
