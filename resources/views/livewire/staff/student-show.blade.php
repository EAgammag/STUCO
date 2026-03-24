<div class="space-y-6">
    @if ($student)
        {{-- Student Profile Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Header with Avatar --}}
            <div class="bg-gradient-to-br from-[#590004] via-[#701005] to-[#a50104] px-8 py-8">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-white/20 text-white flex items-center justify-center text-2xl font-bold shrink-0 ring-4 ring-white/30">
                        {{ strtoupper(substr($student['first_name'] ?? $student['name'], 0, 1)) }}{{ strtoupper(substr($student['last_name'] ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-2xl leading-tight">{{ $student['name'] }}</h1>
                        <p class="text-red-100 text-sm font-mono mt-1.5">{{ $student['student_id'] ?? 'No ID assigned' }}</p>
                        @if ($student['created_at'])
                            <p class="text-red-200 text-xs mt-2">Registered on {{ $student['created_at'] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Personal Information Section --}}
            <div class="p-8">
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-6 bg-[#590004] rounded-full"></div>
                        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest">Personal Information</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">First Name</p>
                            <p class="text-base font-semibold text-gray-900">{{ $student['first_name'] ?: 'Not Provided' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Last Name</p>
                            <p class="text-base font-semibold text-gray-900">{{ $student['last_name'] ?: 'Not Provided' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-5 md:col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</p>
                            <p class="text-base font-semibold text-gray-900 break-all">{{ $student['email'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Academic Information Section --}}
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-6 bg-[#590004] rounded-full"></div>
                        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest">Academic Information</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5 md:col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">College / Department</p>
                            <p class="text-base font-semibold text-gray-900">{{ $student['college'] ?: 'Not Assigned' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Year Level</p>
                            <p class="text-base font-semibold text-gray-900">{{ $student['year_level'] ?: 'Not Assigned' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Section</p>
                            <p class="text-base font-semibold text-gray-900">{{ $student['section'] ?: 'Not Assigned' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Footer --}}
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <a
                    href="{{ route('staff.students') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Roster
                </a>
                <a
                    href="{{ route('staff.students.edit', $student['id']) }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#590004] to-[#a50104] rounded-xl shadow-lg hover:from-[#a50104] hover:to-[#590004] transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Record
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500 font-medium">Student not found</p>
            <a href="{{ route('staff.students') }}" class="mt-4 inline-block text-[#590004] font-semibold hover:underline">
                Return to Student Roster
            </a>
        </div>
    @endif
</div>
