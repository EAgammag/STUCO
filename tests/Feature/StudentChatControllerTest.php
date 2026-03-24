<?php

namespace Tests\Feature;

use App\Models\OffenseRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentChatControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'staff']);

        $this->student = User::factory()->student()->create();
        $this->student->assignRole('student');

        $this->staff = User::factory()->create();
        $this->staff->assignRole('staff');

        OffenseRule::factory()->count(3)->create();
    }

    // ─── Authorization ──────────────────────────────────────────────────────

    public function test_guest_is_redirected_from_chat_endpoint(): void
    {
        $response = $this->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'lang' => 'en',
            'studentName' => 'Test Student',
        ]);

        $response->assertStatus(401);
    }

    public function test_staff_cannot_access_chat_endpoint(): void
    {
        $response = $this->actingAs($this->staff)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'lang' => 'en',
            'studentName' => 'Test',
        ]);

        $response->assertStatus(403);
    }

    // ─── Validation ─────────────────────────────────────────────────────────

    public function test_chat_requires_messages_field(): void
    {
        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'lang' => 'en',
            'studentName' => 'Test Student',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['messages']);
    }

    public function test_chat_requires_valid_lang(): void
    {
        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'lang' => 'jp',
            'studentName' => 'Test Student',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['lang']);
    }

    public function test_chat_requires_student_name(): void
    {
        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'lang' => 'en',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['studentName']);
    }

    public function test_chat_rejects_invalid_message_role(): void
    {
        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'system', 'content' => 'Hello']],
            'lang' => 'en',
            'studentName' => 'Test Student',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['messages.0.role']);
    }

    // ─── API Key Not Configured ─────────────────────────────────────────────

    public function test_chat_returns_503_when_api_key_missing(): void
    {
        config(['services.anthropic.key' => '']);

        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'What is cheating?']],
            'lang' => 'en',
            'studentName' => 'Juan Dela Cruz',
        ]);

        $response->assertStatus(503)->assertJsonFragment(['error' => 'The AI assistant is not configured. Please contact the OSA.']);
    }

    // ─── Successful AI Response ─────────────────────────────────────────────

    public function test_chat_returns_reply_from_anthropic(): void
    {
        config(['services.anthropic.key' => 'test-api-key']);

        Http::fake([
            'api.anthropic.com/*' => Http::response([
                'content' => [['type' => 'text', 'text' => 'Cheating is penalized under our conduct rules.']],
                'model' => 'claude-sonnet-4-20250514',
                'stop_reason' => 'end_turn',
            ], 200),
        ]);

        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'What is cheating?']],
            'lang' => 'en',
            'studentName' => 'Juan Dela Cruz',
        ]);

        $response->assertStatus(200)->assertJsonFragment(['reply' => 'Cheating is penalized under our conduct rules.']);
    }

    public function test_chat_uses_correct_anthropic_headers(): void
    {
        config(['services.anthropic.key' => 'test-key-xyz']);

        Http::fake([
            'api.anthropic.com/*' => Http::response([
                'content' => [['type' => 'text', 'text' => 'Response']],
            ], 200),
        ]);

        $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'lang' => 'fil',
            'studentName' => 'Maria Santos',
        ]);

        Http::assertSent(fn ($request) => $request->hasHeader('x-api-key', 'test-key-xyz') &&
            $request->hasHeader('anthropic-version') &&
            str_contains($request->url(), 'api.anthropic.com')
        );
    }

    // ─── Failed Upstream Response ───────────────────────────────────────────

    public function test_chat_returns_502_when_anthropic_fails(): void
    {
        config(['services.anthropic.key' => 'test-api-key']);

        Http::fake([
            'api.anthropic.com/*' => Http::response(['error' => 'Server error'], 500),
        ]);

        $response = $this->actingAs($this->student)->postJson(route('student.chat'), [
            'messages' => [['role' => 'user', 'content' => 'What is cheating?']],
            'lang' => 'en',
            'studentName' => 'Test Student',
        ]);

        $response->assertStatus(502)->assertJsonFragment(['error' => 'The AI service is temporarily unavailable. Please try again shortly.']);
    }
}
