<?php

namespace App\Services;

use App\Mail\StudentAccountReleasedMail;
use App\Models\Application;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class StudentAccountService
{
    public function getPendingReleaseQueue(int $perPage = 15, ?string $programPrefix = null): LengthAwarePaginator
    {
        if (!Schema::hasColumn('applications', 'account_status')) {
            return new Paginator(
                [],
                0,
                $perPage,
                Paginator::resolveCurrentPage(),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        $rows = DB::table('applications as a')
            ->join('programs as p', 'p.id', '=', 'a.program_applied_id')
            ->join('users as u', function ($join) {
                $join->on('u.email', '=', 'a.email')
                    ->whereNull('u.deleted_at');
            })
            ->join('students as s', function ($join) {
                $join->on('s.user_id', '=', 'u.id')
                    ->whereNull('s.deleted_at');
            })
            ->join('enrollments as e', function ($join) {
                $join->on('e.student_id', '=', 's.id')
                    ->whereNull('e.deleted_at');
            })
            ->leftJoinSub(
                DB::table('payments')
                    ->select('enrollment_id', DB::raw('COALESCE(SUM(amount), 0) AS verified_total'))
                    ->whereNull('deleted_at')
                    ->where('status', 'verified')
                    ->groupBy('enrollment_id'),
                'vp',
                fn($join) => $join->on('vp.enrollment_id', '=', 'e.id')
            )
            ->where('a.workflow_stage', Application::WORKFLOW_CASHIER_PAYMENT)
            ->where('a.account_status', Application::ACCOUNT_STATUS_PENDING)
            ->whereRaw('e.id = (SELECT MAX(e2.id) FROM enrollments e2 WHERE e2.student_id = s.id AND e2.deleted_at IS NULL)')
            ->whereRaw('COALESCE(e.total_amount, 0) > 0')
            ->whereRaw('COALESCE(vp.verified_total, 0) >= COALESCE(e.total_amount, 0)')
            ->select([
                'a.id as application_id',
                'a.app_id',
                'a.first_name',
                'a.last_name',
                'a.program_applied_id',
                'a.account_status',
                'a.payment_status',
                's.student_id',
                's.id as student_pk',
                'e.total_amount',
                DB::raw('COALESCE(vp.verified_total, 0) as verified_total'),
            ])
            ->orderByDesc('a.id')
            ->paginate($perPage);

        if ($programPrefix !== null) {
            $rows = DB::table('applications as a')
                ->join('programs as p', 'p.id', '=', 'a.program_applied_id')
                ->join('users as u', function ($join) {
                    $join->on('u.email', '=', 'a.email')
                        ->whereNull('u.deleted_at');
                })
                ->join('students as s', function ($join) {
                    $join->on('s.user_id', '=', 'u.id')
                        ->whereNull('s.deleted_at');
                })
                ->join('enrollments as e', function ($join) {
                    $join->on('e.student_id', '=', 's.id')
                        ->whereNull('e.deleted_at');
                })
                ->leftJoinSub(
                    DB::table('payments')
                        ->select('enrollment_id', DB::raw('COALESCE(SUM(amount), 0) AS verified_total'))
                        ->whereNull('deleted_at')
                        ->where('status', 'verified')
                        ->groupBy('enrollment_id'),
                    'vp',
                    fn($join) => $join->on('vp.enrollment_id', '=', 'e.id')
                )
                ->where('a.workflow_stage', Application::WORKFLOW_CASHIER_PAYMENT)
                ->where('a.account_status', Application::ACCOUNT_STATUS_PENDING)
                ->where('p.code', 'like', $programPrefix . '%')
                ->whereRaw('e.id = (SELECT MAX(e2.id) FROM enrollments e2 WHERE e2.student_id = s.id AND e2.deleted_at IS NULL)')
                ->whereRaw('COALESCE(e.total_amount, 0) > 0')
                ->whereRaw('COALESCE(vp.verified_total, 0) >= COALESCE(e.total_amount, 0)')
                ->select([
                    'a.id as application_id',
                    'a.app_id',
                    'a.first_name',
                    'a.last_name',
                    'a.program_applied_id',
                    'a.account_status',
                    'a.payment_status',
                    's.student_id',
                    's.id as student_pk',
                    'e.total_amount',
                    DB::raw('COALESCE(vp.verified_total, 0) as verified_total'),
                ])
                ->orderByDesc('a.id')
                ->paginate($perPage);
        }

        $programIds = collect($rows->items())
            ->pluck('program_applied_id')
            ->filter()
            ->unique()
            ->values();

        $programMap = DB::table('programs')
            ->whereIn('id', $programIds)
            ->pluck('name', 'id');

        $rows->getCollection()->transform(function ($row) use ($programMap) {
            $row->student_name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
            $row->program_name = $programMap[$row->program_applied_id] ?? 'N/A';
            $row->computed_payment_status = ((float) $row->verified_total >= (float) $row->total_amount) ? 'paid' : 'pending';

            return $row;
        });

        return $rows;
    }

    public function pendingReleaseCount(?string $programPrefix = null): int
    {
        if (!Schema::hasColumn('applications', 'account_status')) {
            return 0;
        }

        $query = DB::table('applications as a')
            ->join('programs as p', 'p.id', '=', 'a.program_applied_id')
            ->join('users as u', function ($join) {
                $join->on('u.email', '=', 'a.email')
                    ->whereNull('u.deleted_at');
            })
            ->join('students as s', function ($join) {
                $join->on('s.user_id', '=', 'u.id')
                    ->whereNull('s.deleted_at');
            })
            ->join('enrollments as e', function ($join) {
                $join->on('e.student_id', '=', 's.id')
                    ->whereNull('e.deleted_at');
            })
            ->leftJoinSub(
                DB::table('payments')
                    ->select('enrollment_id', DB::raw('COALESCE(SUM(amount), 0) AS verified_total'))
                    ->whereNull('deleted_at')
                    ->where('status', 'verified')
                    ->groupBy('enrollment_id'),
                'vp',
                fn($join) => $join->on('vp.enrollment_id', '=', 'e.id')
            )
            ->where('a.workflow_stage', Application::WORKFLOW_CASHIER_PAYMENT)
            ->where('a.account_status', Application::ACCOUNT_STATUS_PENDING)
            ->whereRaw('e.id = (SELECT MAX(e2.id) FROM enrollments e2 WHERE e2.student_id = s.id AND e2.deleted_at IS NULL)')
            ->whereRaw('COALESCE(e.total_amount, 0) > 0')
            ->whereRaw('COALESCE(vp.verified_total, 0) >= COALESCE(e.total_amount, 0)');

        if ($programPrefix !== null) {
            $query->where('p.code', 'like', $programPrefix . '%');
        }

        return $query->count();
    }

    /**
     * @throws \Exception
     */
    public function releasePortalAccount(Application $application): array
    {
        if (!Schema::hasColumn('applications', 'account_status')) {
            throw new \Exception('Account release fields are missing. Please run database migrations first.');
        }

        $temporaryPassword = 'sccgti2012';

        $releasedData = DB::transaction(function () use ($application, $temporaryPassword) {
            $application = Application::whereKey($application->id)->lockForUpdate()->firstOrFail();

            if ($application->workflow_stage !== Application::WORKFLOW_CASHIER_PAYMENT) {
                throw new \Exception('Only cashier-cleared applicants can be released.');
            }

            if ($application->account_status === Application::ACCOUNT_STATUS_RELEASED) {
                throw new \Exception('Student account has already been released.');
            }

            $user = User::with('student')
                ->where('email', $application->email)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $application->fullName(),
                    'email' => $application->email,
                    'password' => Hash::make($temporaryPassword),
                    'is_active' => true,
                ]);
            } else {
                $user->update([
                    'name' => $application->fullName(),
                    'password' => Hash::make($temporaryPassword),
                    'is_active' => true,
                ]);
            }

            if (!$user->hasRole('student')) {
                $user->assignRole('student');
            }

            $student = $user->student;

            if (!$student) {
                $student = Student::create([
                    'user_id' => $user->id,
                    'student_id' => $this->generateUniqueStudentId(),
                    'program_id' => $application->program_applied_id,
                    'year_level' => $application->year_level,
                    'status' => 'admitted',
                    'date_of_birth' => $application->date_of_birth,
                    'gender' => $application->gender,
                    'address' => $application->address,
                    'contact_number' => $application->contact_number,
                    'guardian_name' => $application->guardian_name,
                    'guardian_contact' => $application->guardian_contact,
                    'admission_date' => now(),
                ]);
            } else {
                if (empty($student->student_id)) {
                    $student->student_id = $this->generateUniqueStudentId();
                }

                $student->program_id = $application->program_applied_id;
                $student->year_level = $application->year_level;
                if ($student->status !== 'active') {
                    $student->status = 'admitted';
                }
                $student->save();
            }

            $latestEnrollment = Enrollment::where('student_id', $student->id)
                ->whereNull('deleted_at')
                ->latest('id')
                ->first();

            if (!$latestEnrollment) {
                throw new \Exception('No enrollment record found for this applicant.');
            }

            $verifiedTotal = DB::table('payments')
                ->where('enrollment_id', $latestEnrollment->id)
                ->whereNull('deleted_at')
                ->where('status', 'verified')
                ->sum('amount');

            if ((float) $verifiedTotal < (float) $latestEnrollment->total_amount || (float) $latestEnrollment->total_amount <= 0) {
                throw new \Exception('Account release requires fully verified cashier payment.');
            }

            $application->update([
                'account_status' => Application::ACCOUNT_STATUS_RELEASED,
                'account_released_at' => now(),
                'payment_status' => 'paid',
            ]);

            return [
                'user' => $user,
                'student' => $student,
                'application' => $application,
            ];
        });

        $portalUrl = route('login');

        Mail::to($releasedData['user']->email)
            ->send(new StudentAccountReleasedMail(
                application: $releasedData['application'],
                student: $releasedData['student'],
                user: $releasedData['user'],
                temporaryPassword: $temporaryPassword,
                portalUrl: $portalUrl,
            ));

        return $releasedData;
    }

    private function generateUniqueStudentId(): string
    {
        $year = now()->format('Y');

        do {
            $candidate = sprintf('%s-%05d', $year, random_int(1, 99999));
        } while (Student::where('student_id', $candidate)->exists());

        return $candidate;
    }
}
