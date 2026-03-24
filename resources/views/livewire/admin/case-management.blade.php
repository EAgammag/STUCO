<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-mahogany">Case Management</h1>
        <p class="text-gray-600">Manage violation cases, generate reports, and track sanctions</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tab Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button wire:click="setTab('cases')" class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'cases' ? 'border-inferno text-inferno' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Cases
                    </div>
                </button>
                <button wire:click="setTab('case-summary')" class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'case-summary' ? 'border-inferno text-inferno' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Case Summary
                    </div>
                </button>
                <button wire:click="setTab('monthly-report')" class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'monthly-report' ? 'border-inferno text-inferno' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Monthly Report
                    </div>
                </button>
                <button wire:click="setTab('by-type-report')" class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'by-type-report' ? 'border-inferno text-inferno' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        By Type
                    </div>
                </button>
            </nav>
        </div>

        {{-- ====================== TAB 1: CASES ====================== --}}
        @if($activeTab === 'cases')
        <div class="p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Cases</p>
                            <p class="text-3xl font-bold text-mahogany mt-1">{{ $statistics['total_cases'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pending Review</p>
                            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $statistics['pending_review'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Sanctions</p>
                            <p class="text-3xl font-bold text-red-600 mt-1">{{ $statistics['active_sanctions'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Resolved</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $statistics['resolved'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Search case #, student, offense..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                </div>
                <select wire:model.live="filterStatus" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                    <option value="">All Statuses</option>
                    <option value="Pending Review">Pending Review</option>
                    <option value="Sanction Active">Sanction Active</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Appealed">Appealed</option>
                </select>
                <select wire:model.live="filterInvestigationType" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                    <option value="">All Types</option>
                    <option value="Tribunal">Tribunal</option>
                    <option value="Summary">Summary</option>
                    <option value="Dean Direct">Dean Direct</option>
                </select>
                <div class="flex items-center gap-3">
                    <select wire:model.live="filterOffenseCategory" class="flex-1 py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                        <option value="">All Categories</option>
                        @foreach($offenseCategories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    <button wire:click="clearFilters" class="px-3 py-2 text-sm text-gray-500 hover:text-inferno border border-gray-300 rounded-lg hover:border-inferno transition-colors">
                        Clear
                    </button>
                </div>
            </div>

            <!-- Cases Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('case_tracking_number')">Case #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offense</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Settled By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sanction Imposed</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions Taken</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($cases as $case)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-mono text-mahogany font-medium">{{ $case->case_tracking_number ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-mahogany text-white flex items-center justify-center text-xs font-bold">
                                            {{ strtoupper(substr($case->student?->first_name ?? '', 0, 1) . substr($case->student?->last_name ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $case->student?->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $case->student?->student_id ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-[200px] truncate" title="{{ $case->offenseRule?->title ?? '' }}">{{ $case->offenseRule?->title ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $case->status === 'Pending Review' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $case->status === 'Sanction Active' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $case->status === 'Resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $case->status === 'Appealed' ? 'bg-purple-100 text-purple-800' : '' }}
                                    ">{{ $case->status }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($case->settled_by)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->settled_by === 'Dean' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $case->settled_by }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    @if($case->sanction_imposed_at)
                                        {{ $case->sanction_imposed_at->format('M d, Y') }}<br>
                                        <span class="text-gray-400">{{ $case->sanction_imposed_at->format('g:i A') }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 max-w-[200px] truncate" title="{{ $case->action_taken ?? '' }}">{{ $case->action_taken ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <button wire:click="viewCase({{ $case->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        @if($case->status === 'Pending Review')
                                            <button wire:click="openResolveCaseModal({{ $case->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Apply Sanction">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>
                                            @if($case->investigation_type === 'Tribunal' && !$case->assigned_to_sdt)
                                                <button wire:click="openAssignSDTModal({{ $case->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 transition-colors" title="Assign SDT">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-gray-500">No cases found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $cases->links() }}
            </div>
        </div>

        {{-- ====================== CASE DETAIL PANEL ====================== --}}
        @if($selectedCase && !$showResolveCaseModal && !$showAssignSDTModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:close-case.window="$wire.closeCase()">
            <div class="flex items-start justify-center min-h-screen pt-4 px-4 pb-20">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCase"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full z-10 my-8">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b">
                        <div>
                            <h3 class="text-lg font-semibold text-mahogany">Case Details</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ $selectedCase->case_tracking_number }}</p>
                        </div>
                        <button wire:click="closeCase" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <!-- Student & Offense Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Student Information</h4>
                                <p class="text-sm"><span class="text-gray-500">Name:</span> {{ $selectedCase->student?->name ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">Student ID:</span> {{ $selectedCase->student?->student_id ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">College:</span> {{ $selectedCase->student?->college ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">Year/Section:</span> {{ $selectedCase->student?->year_level ?? '' }} - {{ $selectedCase->student?->section ?? '' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Case Information</h4>
                                <p class="text-sm"><span class="text-gray-500">Offense:</span> {{ $selectedCase->offenseRule?->title ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">Category:</span> {{ $selectedCase->offenseRule?->category ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">Investigation Type:</span> {{ $selectedCase->investigation_type ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">Status:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $selectedCase->status === 'Pending Review' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $selectedCase->status === 'Sanction Active' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $selectedCase->status === 'Resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $selectedCase->status === 'Appealed' ? 'bg-purple-100 text-purple-800' : '' }}
                                    ">{{ $selectedCase->status }}</span>
                                </p>
                                <p class="text-sm"><span class="text-gray-500">Reported By:</span> {{ $selectedCase->reporter?->name ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="text-gray-500">Date of Incident:</span> {{ $selectedCase->date_of_incident?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Sanction Details -->
                        @if($selectedCase->settled_by || $selectedCase->sanction_imposed_at || $selectedCase->action_taken)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Sanction Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm"><span class="text-gray-500">Settled By:</span>
                                        @if($selectedCase->settled_by)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $selectedCase->settled_by === 'Dean' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $selectedCase->settledByLabel() }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </p>
                                    <p class="text-sm mt-1"><span class="text-gray-500">Applied Sanction:</span> {{ $selectedCase->applied_sanction ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm"><span class="text-gray-500">Sanction Imposed:</span> {{ $selectedCase->sanction_imposed_at?->format('M d, Y g:i A') ?? '—' }}</p>
                                    <p class="text-sm mt-1"><span class="text-gray-500">Sanction Effective:</span> {{ $selectedCase->sanction_effective_at?->format('M d, Y g:i A') ?? '—' }}</p>
                                </div>
                            </div>
                            @if($selectedCase->action_taken)
                                <div class="mt-3">
                                    <p class="text-sm"><span class="text-gray-500">Action Taken:</span></p>
                                    <p class="text-sm mt-1 bg-white rounded p-2 border">{{ $selectedCase->action_taken }}</p>
                                </div>
                            @endif
                        </div>
                        @endif

                        <!-- Incident Description -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Incident Description</h4>
                            <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $selectedCase->incident_description }}</p>
                        </div>

                        <!-- Workflow Log -->
                        @if($selectedCase->workflowLogs && $selectedCase->workflowLogs->count() > 0)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Activity Log</h4>
                            <div class="space-y-2">
                                @foreach($selectedCase->workflowLogs->sortByDesc('created_at') as $log)
                                    <div class="flex items-start gap-3 text-sm">
                                        <div class="w-2 h-2 bg-inferno rounded-full mt-1.5 shrink-0"></div>
                                        <div>
                                            <p class="text-gray-700"><span class="font-medium">{{ $log->action_type }}</span> — {{ $log->action_details }}</p>
                                            <p class="text-xs text-gray-400">{{ $log->created_at->format('M d, Y g:i A') }} by {{ $log->actor?->name ?? 'System' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

        {{-- ====================== TAB 2: CASE SUMMARY ====================== --}}
        @if($activeTab === 'case-summary')
        <div class="p-6">
            @include('livewire.admin.partials.report-period-selector')

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-mahogany">{{ $caseSummary['period'] ?? '' }}</h3>
                <button wire:click="exportCaseSummary" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export to Excel
                </button>
            </div>

            @if(!($caseSummary['hasRecords'] ?? false))
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-blue-800">No complaint recorded within the period ({{ $caseSummary['period'] ?? '' }})</span>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case #</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offense</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Settled By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sanction Imposed</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sanction Effective</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions Taken</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($caseSummary['records'] as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-mono text-mahogany">{{ $record->case_tracking_number ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $record->student?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm max-w-[180px] truncate">{{ $record->offenseRule?->title ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $record->status === 'Pending Review' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $record->status === 'Sanction Active' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $record->status === 'Resolved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $record->status === 'Appealed' ? 'bg-purple-100 text-purple-800' : '' }}
                                        ">{{ $record->status }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($record->settled_by)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $record->settled_by === 'Dean' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">{{ $record->settled_by }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $record->sanction_imposed_at?->format('M d, Y g:i A') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $record->sanction_effective_at?->format('M d, Y g:i A') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600 max-w-[200px] truncate" title="{{ $record->action_taken ?? '' }}">{{ $record->action_taken ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif

        {{-- ====================== TAB 3: MONTHLY REPORT ====================== --}}
        @if($activeTab === 'monthly-report')
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700">Year:</label>
                    <select wire:model.live="reportYear" class="py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button wire:click="exportMonthlyReport" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export to Excel
                </button>
            </div>

            @if(!($monthlyReport['hasRecords'] ?? false))
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-blue-800">No complaint recorded for the year {{ $monthlyReport['year'] ?? '' }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pending</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Active</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Resolved</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">By Dean</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">By OSDW</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($monthlyReport['months'] ?? [] as $month)
                            <tr class="hover:bg-gray-50 {{ $month['total'] === 0 ? 'bg-gray-50/50' : '' }}">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $month['month'] }}</td>
                                <td class="px-4 py-3 text-sm text-center font-semibold {{ $month['total'] > 0 ? 'text-mahogany' : 'text-gray-400' }}">{{ $month['total'] }}</td>
                                @if($month['total'] === 0)
                                    <td colspan="5" class="px-4 py-3 text-xs text-gray-400 text-center italic">No complaint recorded</td>
                                @else
                                    <td class="px-4 py-3 text-sm text-center text-amber-600">{{ $month['pending'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-red-600">{{ $month['active'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-green-600">{{ $month['resolved'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center">{{ $month['by_dean'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center">{{ $month['by_osdw'] }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ====================== TAB 4: BY TYPE REPORT ====================== --}}
        @if($activeTab === 'by-type-report')
        <div class="p-6">
            @include('livewire.admin.partials.report-period-selector')

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-mahogany">{{ $byTypeReport['period'] ?? '' }}</h3>
                <button wire:click="exportByTypeReport" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export to Excel
                </button>
            </div>

            @if(!($byTypeReport['hasRecords'] ?? false))
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-blue-800">No complaint recorded within the period ({{ $byTypeReport['period'] ?? '' }})</span>
                </div>
            @else
                <!-- Category Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach($byTypeReport['categories'] ?? [] as $cat)
                        @if($cat['total'] > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <h4 class="text-sm font-semibold text-mahogany mb-3">{{ $cat['category'] }}</h4>
                            <p class="text-2xl font-bold text-gray-900">{{ $cat['total'] }} <span class="text-sm font-normal text-gray-500">cases</span></p>
                            <div class="mt-3 space-y-1 text-xs">
                                <div class="flex justify-between"><span class="text-gray-500">Pending</span><span class="text-amber-600 font-medium">{{ $cat['pending'] }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Active</span><span class="text-red-600 font-medium">{{ $cat['active'] }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Resolved</span><span class="text-green-600 font-medium">{{ $cat['resolved'] }}</span></div>
                                <div class="flex justify-between border-t pt-1 mt-1"><span class="text-gray-500">By Dean</span><span class="font-medium">{{ $cat['by_dean'] }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">By OSDW</span><span class="font-medium">{{ $cat['by_osdw'] }}</span></div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <!-- Summary Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pending</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Active</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Resolved</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">By Dean</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">By OSDW</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($byTypeReport['categories'] ?? [] as $cat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $cat['category'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center font-semibold {{ $cat['total'] > 0 ? 'text-mahogany' : 'text-gray-400' }}">{{ $cat['total'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-amber-600">{{ $cat['pending'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-red-600">{{ $cat['active'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-green-600">{{ $cat['resolved'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center">{{ $cat['by_dean'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center">{{ $cat['by_osdw'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ====================== RESOLVE CASE MODAL ====================== --}}
    @if($showResolveCaseModal && $selectedCase)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('showResolveCaseModal', false)"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full z-10">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-mahogany">Apply Sanction</h3>
                    <p class="text-sm text-gray-500">Case #{{ $selectedCase->case_tracking_number }}</p>
                </div>
                <form wire:submit="resolveCase" class="p-6 space-y-4">
                    <!-- Settled By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Settled By <span class="text-red-500">*</span></label>
                        <select wire:model="settledBy" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                            <option value="">Select...</option>
                            <option value="Dean">Dean</option>
                            <option value="OSDW">OSDW</option>
                        </select>
                        @error('settledBy') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Sanction Imposed Date & Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sanction Imposed Date <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="sanctionImposedDate" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                            @error('sanctionImposedDate') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                            <input type="time" wire:model="sanctionImposedTime" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                            @error('sanctionImposedTime') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Sanction Effective Date & Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sanction Effective Date</label>
                            <input type="date" wire:model="sanctionEffectiveDate" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Effective Time</label>
                            <input type="time" wire:model="sanctionEffectiveTime" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                        </div>
                    </div>

                    <!-- Action Taken -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Action Taken <span class="text-red-500">*</span></label>
                        <textarea wire:model="actionTaken" rows="3" placeholder="Describe the actions taken..." class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm"></textarea>
                        @error('actionTaken') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showResolveCaseModal', false)" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-inferno rounded-lg hover:bg-black-cherry transition-colors">
                            <span wire:loading.remove wire:target="resolveCase">Apply Sanction</span>
                            <span wire:loading wire:target="resolveCase">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- ====================== ASSIGN SDT MODAL ====================== --}}
    @if($showAssignSDTModal && $selectedCase)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('showAssignSDTModal', false)"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full z-10">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-mahogany">Assign Student Disciplinary Tribunal</h3>
                    <p class="text-sm text-gray-500">Select exactly 5 members (CSU Section G.1)</p>
                </div>
                <form wire:submit="assignSDT" class="p-6">
                    @error('selectedSDTMembers') <p class="text-sm text-red-500 mb-3">{{ $message }}</p> @enderror
                    <div class="max-h-60 overflow-y-auto space-y-2 mb-4">
                        @foreach($sdtMembers as $member)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" wire:model="selectedSDTMembers" value="{{ $member['id'] }}" class="rounded border-gray-300 text-inferno focus:ring-inferno">
                                <span class="text-sm text-gray-700">{{ $member['name'] }}</span>
                                <span class="text-xs text-gray-400">({{ $member['role'] }})</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mb-4">Selected: {{ count($selectedSDTMembers) }}/5</p>
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="$set('showAssignSDTModal', false)" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-inferno rounded-lg hover:bg-black-cherry transition-colors" {{ count($selectedSDTMembers) !== 5 ? 'disabled' : '' }}>Assign Tribunal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
