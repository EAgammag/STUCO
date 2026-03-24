<?php

namespace App\Livewire\Admin;

use App\Models\AuthAuditLog;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogs extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $eventType = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    #[Url]
    public string $userId = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingEventType(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingUserId(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'eventType', 'dateFrom', 'dateTo', 'userId']);
        $this->resetPage();
    }

    public function render()
    {
        $query = AuthAuditLog::query()
            ->with('user')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('email', 'like', '%'.$this->search.'%')
                        ->orWhere('ip_address', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->eventType, function ($q) {
                $q->where('event_type', $this->eventType);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->userId, function ($q) {
                $q->where('user_id', $this->userId);
            })
            ->orderBy('created_at', 'desc');

        // Get unique event types for filter dropdown
        $eventTypes = AuthAuditLog::select('event_type')
            ->distinct()
            ->pluck('event_type')
            ->sort()
            ->values();

        // Get users that have audit logs
        $users = User::whereHas('auditLogs')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('livewire.admin.audit-logs', [
            'logs' => $query->paginate(20),
            'eventTypes' => $eventTypes,
            'users' => $users,
        ])->layout('layouts.admin', ['title' => 'Audit Trails & Logs']);
    }
}
