<?php

namespace App\Http\Controllers;

use App\Models\OffenseRule;
use App\Models\ViolationRecord;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentConductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the student's conduct dashboard.
     *
     * CRITICAL: Implements strict data isolation - students can ONLY query their own UUID/ID.
     * Utilizes eager loading to prevent N+1 query problems.
     */
    public function index(): View
    {
        $studentId = Auth::id();

        // Eager load related offense details to prevent N+1 queries
        // Uses ->with() to load relationships in a single query
        $violationRecords = ViolationRecord::with(['offenseRule', 'reporter'])
            ->where('student_id', $studentId)
            ->orderBy('date_of_incident', 'desc')
            ->get();

        // Calculate dynamic conduct standing
        $activeSanctionsCount = $violationRecords->where('status', 'Sanction Active')->count();
        $standing = $activeSanctionsCount > 0 ? 'Under Sanction' : 'Good Standing';

        // Additional dashboard metrics
        $pendingReviewCount = $violationRecords->where('status', 'Pending Review')->count();
        $resolvedCount = $violationRecords->where('status', 'Resolved')->count();
        $appealedCount = $violationRecords->where('status', 'Appealed')->count();

        return view('student.dashboard', compact(
            'violationRecords',
            'standing',
            'activeSanctionsCount',
            'pendingReviewCount',
            'resolvedCount',
            'appealedCount'
        ));
    }

    /**
     * Display a specific violation record.
     *
     * CRITICAL: Authorization gate prevents IDOR vulnerability.
     * URL manipulation (e.g., /student/records/15 → /student/records/16) is blocked by policy.
     */
    public function show(ViolationRecord $record): View
    {
        // Policy check: ensures authenticated user owns this record
        $this->authorize('view', $record);

        // Eager load relationships for detailed view
        $record->load(['offenseRule', 'reporter']);

        return view('student.record-detail', compact('record'));
    }

    /**
     * Display all conduct records for the authenticated student.
     *
     * This is a dedicated records page separate from the dashboard.
     */
    public function records(): View
    {
        $studentId = Auth::id();

        // Eager load related offense details to prevent N+1 queries
        $violationRecords = ViolationRecord::with(['offenseRule', 'reporter'])
            ->where('student_id', $studentId)
            ->orderBy('date_of_incident', 'desc')
            ->get();

        // Calculate metrics for summary cards
        $activeSanctionsCount = $violationRecords->where('status', 'Sanction Active')->count();
        $pendingReviewCount = $violationRecords->where('status', 'Pending Review')->count();
        $resolvedCount = $violationRecords->where('status', 'Resolved')->count();
        $appealedCount = $violationRecords->where('status', 'Appealed')->count();

        return view('student.records', compact(
            'violationRecords',
            'activeSanctionsCount',
            'pendingReviewCount',
            'resolvedCount',
            'appealedCount'
        ));
    }

    /**
     * Display all offense rules from the CSU Student Manual.
     *
     * Students can browse all university policies and offense codes.
     */
    public function offenseRules(): View
    {
        // Get all active offense rules ordered by code
        $offenseRules = OffenseRule::where('is_active', true)
            ->orderBy('code')
            ->get();

        // Get unique categories and severity levels for filters
        $categories = OffenseRule::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $severityLevels = OffenseRule::where('is_active', true)
            ->distinct()
            ->pluck('severity_level')
            ->sort()
            ->values();

        $offenseJson = json_encode(
            $offenseRules->map(fn ($o) => [
                'code' => $o->code,
                'title' => $o->title,
                'description' => $o->description,
                'category' => $o->category,
                'severity_level' => $o->severity_level,
                'gravity' => $o->gravity,
                'standard_sanction' => $o->standard_sanction,
                'first_offense_sanction' => $o->first_offense_sanction,
                'second_offense_sanction' => $o->second_offense_sanction,
                'third_offense_sanction' => $o->third_offense_sanction,
                'legal_reference' => $o->legal_reference,
                'requires_tribunal' => $o->requires_tribunal,
            ])->values(),
            JSON_HEX_TAG | JSON_HEX_AMP
        );

        return view('student.offense-rules', compact(
            'offenseRules',
            'categories',
            'severityLevels',
            'offenseJson'
        ));
    }
}
