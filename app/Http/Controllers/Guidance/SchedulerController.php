<?php

namespace App\Http\Controllers\Guidance;

use App\Http\Controllers\Controller;
use App\Models\GuidanceInterviewSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SchedulerController extends Controller
{
    public function index(): View
    {
        $this->deactivateExpiredSlots();

        $slots = GuidanceInterviewSlot::with('application.program')
            ->orderByDesc('is_active')
            ->orderBy('interview_date')
            ->orderBy('start_time')
            ->paginate(20);

        return view('guidance.scheduler.index', compact('slots'));
    }

    public function logs(): View
    {
        $this->deactivateExpiredSlots();

        $slotLogs = GuidanceInterviewSlot::with('application.program', 'creator')
            ->orderByDesc('deactivated_at')
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('guidance.scheduler.logs', compact('slotLogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'form_type' => ['required', 'in:jhs,shs'],
            'interview_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        GuidanceInterviewSlot::create([
            'form_type' => $data['form_type'],
            'interview_date' => $data['interview_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_active' => true,
            'deactivated_at' => null,
            'deactivation_reason' => null,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Interview schedule slot added.');
    }

    public function toggle(GuidanceInterviewSlot $slot): RedirectResponse
    {
        $nextState = !$slot->is_active;

        $slot->update([
            'is_active' => $nextState,
            'deactivated_at' => $nextState ? null : now(),
            'deactivation_reason' => $nextState ? null : 'manual',
        ]);

        return back()->with('success', $slot->is_active ? 'Interview slot activated.' : 'Interview slot deactivated.');
    }

    public function destroy(GuidanceInterviewSlot $slot): RedirectResponse
    {
        if ($slot->application()->exists()) {
            return back()->with('error', 'Cannot delete occupied schedule slot.');
        }

        $slot->delete();

        return back()->with('success', 'Interview slot deleted.');
    }

    private function deactivateExpiredSlots(): void
    {
        GuidanceInterviewSlot::where('is_active', true)
            ->whereDate('interview_date', '<', now()->toDateString())
            ->update([
                'is_active' => false,
                'deactivated_at' => now(),
                'deactivation_reason' => 'expired',
            ]);
    }
}
