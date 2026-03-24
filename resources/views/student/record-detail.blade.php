<x-student-app-layout>
    <x-slot name="header">
        Violation Record Details
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto space-y-6">
        
        <a href="{{ route('student.dashboard') }}" 
           class="inline-flex items-center text-sm font-medium text-[#590004] hover:text-[#a50104] transition-colors mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
            
        {{-- Status Banner --}}
        @php
            $statusConfig = [
                'Pending Review' => [
                    'bg' => 'bg-yellow-50',
                    'border' => 'border-yellow-500',
                    'text' => 'text-yellow-800',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
                'Sanction Active' => [
                    'bg' => 'bg-red-50',
                    'border' => 'border-[#a50104]',
                    'text' => 'text-[#a50104]',
                    'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
                ],
                'Resolved' => [
                    'bg' => 'bg-green-50',
                    'border' => 'border-green-500',
                    'text' => 'text-green-800',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
                'Appealed' => [
                    'bg' => 'bg-blue-50',
                    'border' => 'border-blue-500',
                    'text' => 'text-blue-800',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                ]
            ];
            $config = $statusConfig[$record->status];
        @endphp

        <div class="rounded-2xl overflow-hidden {{ $config['bg'] }} border-2 {{ $config['border'] }} shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <svg class="w-10 h-10 {{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="{{ $config['text'] }} font-bold text-2xl">{{ $record->status }}</h3>
                            <p class="text-gray-600 text-sm mt-1">Record ID: #{{ $record->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Offense Information --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-[#250001] font-bold text-lg">Offense Information</h3>
            </div>
            <div class="p-8 space-y-5">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Offense Code</label>
                    <p class="text-[#250001] font-bold text-lg mt-1">{{ $record->offenseRule->code }}</p>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Offense Title</label>
                    <p class="text-[#250001] font-semibold text-xl mt-1">{{ $record->offenseRule->title }}</p>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                    <p class="text-gray-700 mt-1 leading-relaxed">{{ $record->offenseRule->description }}</p>
                </div>
                    
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Category</label>
                        <p class="text-[#250001] font-medium mt-1">{{ $record->offenseRule->category }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Severity Level</label>
                        @php
                            $severityColors = [
                                'Minor' => 'bg-yellow-100 text-yellow-800',
                                'Moderate' => 'bg-orange-100 text-orange-800',
                                'Major' => 'bg-red-100 text-red-800',
                                'Severe' => 'bg-[#a50104] text-white',
                            ];
                        @endphp
                        <span class="inline-block mt-1 px-3 py-1 text-sm font-bold rounded-full {{ $severityColors[$record->offenseRule->severity_level] }}">
                            {{ $record->offenseRule->severity_level }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Standard Sanction</label>
                        <p class="text-[#250001] font-medium mt-1">{{ $record->offenseRule->standard_sanction }}</p>
                    </div>
                </div>
                </div>
            </div>

        {{-- Incident Details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-[#250001] font-bold text-lg">Incident Details</h3>
            </div>
            <div class="p-8 space-y-5">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date of Incident</label>
                    <p class="text-[#250001] font-semibold text-lg mt-1">
                        {{ $record->date_of_incident->format('F d, Y') }}
                    </p>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Incident Description</label>
                    <div class="mt-2 p-4 bg-[#f3f3f3] rounded-xl border border-gray-200">
                        <p class="text-gray-800 leading-relaxed">{{ $record->incident_description }}</p>
                    </div>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Reported By</label>
                    <p class="text-gray-700 font-medium mt-1">{{ $record->reporter->name }}</p>
                </div>
                
                @if($record->resolution_date)
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Resolution Date</label>
                        <p class="text-gray-700 font-medium mt-1">
                            {{ $record->resolution_date->format('F d, Y') }}
                        </p>
                    </div>
                @endif
                
                @if($record->resolution_notes)
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Resolution Notes</label>
                        <div class="mt-2 p-4 bg-green-50 rounded-xl border border-green-200">
                            <p class="text-gray-800 leading-relaxed">{{ $record->resolution_notes }}</p>
                        </div>
                    </div>
                @endif
                </div>
            </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-[#250001] font-bold text-lg">Record Timeline</h3>
            </div>
            <div class="p-8">
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-[#250001] flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            @if(!$record->isResolved())
                                <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-sm font-semibold text-[#250001]">Record Created</p>
                            <p class="text-xs text-gray-500">{{ $record->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full {{ $record->status === 'Sanction Active' ? 'bg-[#a50104]' : 'bg-gray-300' }} flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            @if($record->isResolved())
                                <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-sm font-semibold text-gray-700">Current Status: {{ $record->status }}</p>
                            <p class="text-xs text-gray-500">{{ $record->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($record->isResolved())
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-green-700">Resolved</p>
                                @if($record->resolution_date)
                                    <p class="text-xs text-gray-500">{{ $record->resolution_date->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                </div>
            </div>

        {{-- Read-Only Notice --}}
        <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg shadow-sm p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-800">Read-Only Access</p>
                    <p class="text-xs text-yellow-700 mt-1">
                        This is a read-only view of your institutional conduct record. To dispute this record or submit an appeal, please contact the Office of Student Conduct.
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-student-app-layout>
