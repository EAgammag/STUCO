<?php

namespace App\Policies;

use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncidentReportPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Staff can view their own reports, administrators can view all.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['staff', 'administrator']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * CRITICAL: Staff can ONLY view reports they submitted.
     * Administrators can view all reports.
     */
    public function view(User $user, IncidentReport $incidentReport): Response
    {
        // Administrators can view all reports
        if ($user->hasRole('administrator')) {
            return Response::allow();
        }

        // Staff can ONLY view reports they submitted
        if ($user->hasRole('staff') && $user->id === $incidentReport->reporter_id) {
            return Response::allow();
        }

        // Deny with explicit message for unauthorized access attempts
        return Response::denyWithStatus(
            403,
            'You are not authorized to view this incident report.'
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * Staff and administrators can create incident reports.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['staff', 'administrator']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * Only administrators can update incident reports.
     * Staff have READ-ONLY access after submission.
     */
    public function update(User $user, IncidentReport $incidentReport): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only administrators can delete incident reports.
     */
    public function delete(User $user, IncidentReport $incidentReport): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IncidentReport $incidentReport): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IncidentReport $incidentReport): bool
    {
        return $user->hasRole('administrator');
    }
}
