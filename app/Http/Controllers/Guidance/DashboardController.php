<?php

namespace App\Http\Controllers\Guidance;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\GuidanceInterviewSlot;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'queued' => Application::workflowStage(Application::WORKFLOW_GUIDANCE_REVIEW)->count(),
            'scheduled' => GuidanceInterviewSlot::where('is_active', true)
                ->whereDate('interview_date', '>=', now()->toDateString())
                ->count(),
            'submitted' => Application::workflowStage(Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED)->count(),
            'archived' => Application::archived()->count(),
        ];

        $applications = Application::with('program', 'interviewSlot')
            ->whereIn('workflow_stage', [
                Application::WORKFLOW_GUIDANCE_REVIEW,
                Application::WORKFLOW_INTERVIEW_SCHEDULED,
                Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED,
            ])
            ->latest('forwarded_to_guidance_at')
            ->take(8)
            ->get();

        return view('guidance.dashboard', compact('stats', 'applications'));
    }
}
