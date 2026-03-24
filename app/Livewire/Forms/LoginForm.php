<?php

namespace App\Livewire\Forms;

use App\Models\AuthAuditLog;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            // Log failed authentication attempt
            $this->logAuthenticationAttempt('login_failed');

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Log successful authentication
        $this->logAuthenticationAttempt('login_success', Auth::id());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        // Log lockout event
        $this->logAuthenticationAttempt('lockout');

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    /**
     * Log authentication attempt for audit trail.
     */
    protected function logAuthenticationAttempt(string $eventType, ?int $userId = null): void
    {
        AuthAuditLog::create([
            'user_id' => $userId,
            'email' => $this->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'event_type' => $eventType,
            'additional_data' => [
                'remember' => $this->remember,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }
}
