<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ViolationRecord;
use Illuminate\Auth\Access\Response;

class ViolationRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Students can view their own list, staff/administrator can view all
        return $user->hasAnyRole(['student', 'staff', 'administrator']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * CRITICAL: Prevents IDOR (Insecure Direct Object Reference) vulnerability.
     * A student MUST only be able to view their own violation records.
     */
    public function view(User $user, ViolationRecord $violationRecord): Response
    {
        // Staff and administrators can view all records
        if ($user->hasAnyRole(['staff', 'administrator'])) {
            return Response::allow();
        }

        // Students can ONLY view their own records
        if ($user->hasRole('student') && $user->id === $violationRecord->student_id) {
            return Response::allow();
        }

        // Deny with explicit message for unauthorized access attempts
        return Response::denyWithStatus(
            403,
            'You are not authorized to view this violation record.'
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * Only staff and admins can create violation records.
     * Students cannot create their own violations.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['staff', 'administrator']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * Students have READ-ONLY access to their records.
     * Only staff and admins can make changes.
     */
    public function update(User $user, ViolationRecord $violationRecord): bool
    {
        return $user->hasAnyRole(['staff', 'administrator']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only admins can delete violation records.
     * This ensures institutional record integrity.
     */
    public function delete(User $user, ViolationRecord $violationRecord): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ViolationRecord $violationRecord): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ViolationRecord $violationRecord): bool
    {
        return $user->hasRole('administrator');
    }
}
