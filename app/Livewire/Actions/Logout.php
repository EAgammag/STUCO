<?php

namespace App\Livewire\Actions;

use App\Models\AuthAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        $user = Auth::user();

        // Log logout event before logging out
        if ($user) {
            AuthAuditLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'event_type' => 'logout',
                'additional_data' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
