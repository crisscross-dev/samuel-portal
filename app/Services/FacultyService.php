<?php

namespace App\Services;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacultyService
{
    /**
     * Create a faculty account (User + Faculty record) inside a transaction.
     */
    public function createFaculty(array $data): Faculty
    {
        return DB::transaction(function () use ($data) {
            // 1. Create user record
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => $data['password'] ?? Str::random(12),
                'is_active' => true,
            ]);

            // 2. Assign faculty role
            $user->assignRole('faculty');

            // 3. Create faculty profile
            $faculty = Faculty::create([
                'user_id'        => $user->id,
                'employee_id'    => $data['employee_id'],
                'department_id'  => $data['department_id'],
                'specialization' => $data['specialization'] ?? null,
                'is_active'      => $data['is_active'] ?? true,
            ]);

            return $faculty->load('user', 'department');
        });
    }

    /**
     * Update an existing faculty account (User + Faculty record).
     */
    public function updateFaculty(Faculty $faculty, array $data): Faculty
    {
        return DB::transaction(function () use ($faculty, $data) {
            // Update user record
            $userData = array_filter([
                'name'  => $data['name'] ?? null,
                'email' => $data['email'] ?? null,
            ]);

            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];
            }

            if (!empty($userData)) {
                $faculty->user->update($userData);
            }

            // Update faculty record
            $faculty->update([
                'employee_id'    => $data['employee_id'] ?? $faculty->employee_id,
                'department_id'  => $data['department_id'] ?? $faculty->department_id,
                'specialization' => $data['specialization'] ?? $faculty->specialization,
                'is_active'      => $data['is_active'] ?? $faculty->is_active,
            ]);

            return $faculty->fresh(['user', 'department']);
        });
    }

    /**
     * Toggle active status. Deactivated faculty also has their user account deactivated.
     */
    public function toggleActive(Faculty $faculty): Faculty
    {
        return DB::transaction(function () use ($faculty) {
            $newStatus = !$faculty->is_active;

            $faculty->update(['is_active' => $newStatus]);
            $faculty->user->update(['is_active' => $newStatus]);

            // If deactivating, remove as department head
            if (!$newStatus) {
                $faculty->headedDepartment?->update(['head_faculty_id' => null]);
            }

            return $faculty->fresh(['user', 'department']);
        });
    }

    /**
     * Generate the next employee ID automatically.
     */
    public function generateEmployeeId(): string
    {
        $last = Faculty::withTrashed()
            ->where('employee_id', 'LIKE', 'EMP-%')
            ->orderByRaw("CAST(SUBSTRING(employee_id, 5) AS UNSIGNED) DESC")
            ->value('employee_id');

        $nextNum = $last ? ((int) substr($last, 4)) + 1 : 1;

        return 'EMP-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
    }
}
