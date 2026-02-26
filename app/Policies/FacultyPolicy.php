<?php

namespace App\Policies;

use App\Models\Faculty;
use App\Models\User;

class FacultyPolicy
{
    /**
     * Admin can do everything.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'registrar']);
    }

    public function view(User $user, Faculty $faculty): bool
    {
        // Admin & registrar can view any faculty
        if ($user->hasAnyRole(['admin', 'registrar'])) return true;

        // Faculty can view their own profile
        if ($user->hasRole('faculty') && $user->faculty) {
            return $user->faculty->id === $faculty->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Faculty $faculty): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Faculty $faculty): bool
    {
        return $user->hasRole('admin');
    }

    public function toggleActive(User $user, Faculty $faculty): bool
    {
        return $user->hasRole('admin');
    }
}
