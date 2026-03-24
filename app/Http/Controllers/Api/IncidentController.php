<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncidentResource;
use App\Models\IncidentReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class IncidentController extends Controller
{
    /**
     * Display a paginated listing of incidents.
     * Required Role: Staff, Administrator
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $incidents = IncidentReport::query()
            ->with(['reporter', 'student', 'offense'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->reporter_id, fn ($q, $id) => $q->where('reporter_id', $id))
            ->when($request->student_id, fn ($q, $id) => $q->where('student_id', $id))
            ->when($request->search, fn ($q, $search) => $q->where('tracking_number', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return IncidentResource::collection($incidents);
    }

    /**
     * Store a new incident report.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'offense_id' => ['sometimes', 'exists:offense_rules,id'],
            'report_type' => ['required', 'string', 'in:formal,informal'],
            'description' => ['required', 'string', 'min:10'],
        ]);

        $incident = IncidentReport::create([
            'tracking_number' => 'INC-'.strtoupper(Str::random(8)),
            'reporter_id' => $request->user()->id,
            'student_id' => $validated['student_id'],
            'offense_id' => $validated['offense_id'] ?? null,
            'report_type' => $validated['report_type'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Incident reported successfully',
            'incident' => new IncidentResource($incident->load(['reporter', 'student', 'offense'])),
        ], 201);
    }

    /**
     * Display the specified incident.
     */
    public function show(IncidentReport $incident): JsonResponse
    {
        return response()->json([
            'incident' => new IncidentResource($incident->load(['reporter', 'student', 'offense'])),
        ]);
    }

    /**
     * Update the specified incident.
     */
    public function update(Request $request, IncidentReport $incident): JsonResponse
    {
        $validated = $request->validate([
            'offense_id' => ['sometimes', 'exists:offense_rules,id'],
            'report_type' => ['sometimes', 'string', 'in:formal,informal'],
            'description' => ['sometimes', 'string', 'min:10'],
            'status' => ['sometimes', 'string', 'in:pending,under_review,resolved,dismissed'],
        ]);

        $incident->update($validated);

        return response()->json([
            'message' => 'Incident updated successfully',
            'incident' => new IncidentResource($incident->fresh()->load(['reporter', 'student', 'offense'])),
        ]);
    }

    /**
     * Remove the specified incident.
     */
    public function destroy(IncidentReport $incident): JsonResponse
    {
        $incident->delete();

        return response()->json([
            'message' => 'Incident deleted successfully',
        ]);
    }
}
