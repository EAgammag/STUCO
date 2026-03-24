{{-- Shared Period Selector for Report Tabs --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Period Type</label>
        <select wire:model.live="reportPeriodType" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
            <option value="semestral">Semestral</option>
            <option value="custom">Custom Range</option>
        </select>
    </div>

    @if($reportPeriodType === 'monthly')
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
            <select wire:model.live="reportMonth" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::create(null, $m)->format('F') }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
            <select wire:model.live="reportYear" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    @elseif($reportPeriodType === 'quarterly')
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Quarter</label>
            <select wire:model.live="reportQuarter" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                <option value="1">Q1 (Jan - Mar)</option>
                <option value="2">Q2 (Apr - Jun)</option>
                <option value="3">Q3 (Jul - Sep)</option>
                <option value="4">Q4 (Oct - Dec)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
            <select wire:model.live="reportYear" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    @elseif($reportPeriodType === 'semestral')
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Semester</label>
            <select wire:model.live="reportSemester" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                <option value="1st">1st Semester (Jun - Oct)</option>
                <option value="2nd">2nd Semester (Nov - Mar)</option>
                <option value="summer">Summer (Apr - May)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
            <select wire:model.live="reportYear" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    @elseif($reportPeriodType === 'custom')
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
            <input type="date" wire:model.live="reportStartDate" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
            <input type="date" wire:model.live="reportEndDate" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-inferno focus:border-inferno text-sm">
        </div>
    @endif
</div>
