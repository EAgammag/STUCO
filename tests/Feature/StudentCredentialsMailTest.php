<?php

namespace Tests\Feature;

use App\Mail\StudentCredentialsMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentCredentialsMailTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private string $rawPassword = 'T3mp@Pass!';

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'student']);

        $this->student = User::factory()->student()->create([
            'name' => 'Maria Santos',
            'email' => 'maria.santos@csu.edu.ph',
        ]);
        $this->student->assignRole('student');
    }

    public function test_mailable_has_correct_subject(): void
    {
        $mail = new StudentCredentialsMail($this->student, $this->rawPassword);

        $mail->assertHasSubject('Action Required: Your CSU SCMS Account Credentials');
    }

    public function test_mailable_uses_credentials_view(): void
    {
        $mail = new StudentCredentialsMail($this->student, $this->rawPassword);

        $mail->assertSeeInHtml($this->student->name);
        $mail->assertSeeInHtml($this->student->student_id);
        $mail->assertSeeInHtml($this->rawPassword);
    }

    public function test_mailable_contains_login_link(): void
    {
        $mail = new StudentCredentialsMail($this->student, $this->rawPassword);

        $mail->assertSeeInHtml(route('login'));
    }

    public function test_mailable_is_queued(): void
    {
        $mail = new StudentCredentialsMail($this->student, $this->rawPassword);

        $this->assertInstanceOf(ShouldQueue::class, $mail);
    }

    public function test_mail_is_sent_to_student_email(): void
    {
        Mail::fake();

        Mail::to($this->student->email)
            ->send(new StudentCredentialsMail($this->student, $this->rawPassword));

        // ShouldQueue means the mail is queued, not sent synchronously
        Mail::assertQueued(StudentCredentialsMail::class, function (StudentCredentialsMail $mail) {
            return $mail->student->id === $this->student->id
                && $mail->password === $this->rawPassword;
        });
    }

    public function test_raw_password_is_exposed_in_email_not_hash(): void
    {
        $mail = new StudentCredentialsMail($this->student, $this->rawPassword);

        // The raw password should be visible, not a bcrypt hash
        $mail->assertSeeInHtml($this->rawPassword);
        $mail->assertDontSeeInHtml('$2y$');
    }
}
