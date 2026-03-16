<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Application;
use App\Services\AssessmentService;
use App\Services\StudentAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SidebarBadgeController extends Controller
{
    public function __construct(
        private readonly AssessmentService $assessmentService,
        private readonly StudentAccountService $studentAccountService,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['counts' => []], 401);
        }

        if ($user->hasAnyRole(['registrar', 'jhs-registrar', 'shs-registrar'])) {
            return response()->json([
                'counts' => $this->registrarCounts($this->programPrefixForRegistrar($user)),
                'refreshed_at' => now()->toIso8601String(),
            ]);
        }

        if ($user->hasRole('cashier')) {
            return response()->json([
                'counts' => $this->cashierCounts(),
                'refreshed_at' => now()->toIso8601String(),
            ]);
        }

        if ($user->hasRole('guidance')) {
            return response()->json([
                'counts' => $this->guidanceCounts(),
                'refreshed_at' => now()->toIso8601String(),
            ]);
        }

        return response()->json([
            'counts' => [],
            'refreshed_at' => now()->toIso8601String(),
        ]);
    }

    private function registrarCounts(?string $programPrefix = null): array
    {
        $base = Application::query()->where('is_active', true);
        if ($programPrefix !== null) {
            $base->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'));
        }

        return [
            'registrar.admission' => (clone $base)->where('workflow_stage', Application::WORKFLOW_SUBMITTED)->count(),
            'registrar.exam' => (clone $base)->where('workflow_stage', Application::WORKFLOW_EXAM_APPROVED)->whereNull('exam_result')->count(),
            'registrar.request' => (clone $base)->where('workflow_stage', Application::WORKFLOW_REGISTRAR_REQUIREMENTS)->count(),
            'registrar.enrollment' => (clone $base)->where('workflow_stage', Application::WORKFLOW_ENROLLMENT)->count(),
            'registrar.student_accounts' => $this->studentAccountService->pendingReleaseCount($programPrefix),
        ];
    }

    private function cashierCounts(): array
    {
        $enrollments = Enrollment::with(['payments'])
            ->whereIn('status', ['pending', 'assessed', 'enrolled'])
            ->get();

        $count = 0;

        foreach ($enrollments as $enrollment) {
            try {
                $breakdown = $this->assessmentService->getBreakdown($enrollment);
                $balance = (float) ($breakdown['balance'] ?? $enrollment->balance());

                if ($balance > 0) {
                    $count++;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return [
            'cashier.payment' => $count,
        ];
    }

    private function guidanceCounts(): array
    {
        $row = DB::selectOne(
            'SELECT
                SUM(CASE
                    WHEN workflow_stage = ?
                    AND interview_result IS NULL
                    AND is_active = 1
                    AND is_archived = 0
                    THEN 1 ELSE 0 END
                ) AS interview_queue,
                SUM(CASE
                    WHEN workflow_stage IN (?, ?)
                    AND interview_result IS NULL
                    AND is_active = 1
                    AND is_archived = 0
                    THEN 1 ELSE 0 END
                ) AS interview_remark
            FROM applications',
            [
                Application::WORKFLOW_GUIDANCE_REVIEW,
                Application::WORKFLOW_INTERVIEW_SCHEDULED,
                Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED,
            ]
        );

        return [
            'guidance.interview_queue' => (int) ($row->interview_queue ?? 0),
            'guidance.interview_remark' => (int) ($row->interview_remark ?? 0),
        ];
    }

    private function programPrefixForRegistrar($user): ?string
    {
        if ($user->hasRole('jhs-registrar')) {
            return 'JHS-';
        }

        if ($user->hasRole('shs-registrar')) {
            return 'SHS-';
        }

        return null;
    }
}
