<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExamScheduleController extends Controller
{
    /**
     * List all exam schedules with booking counts.
     */
    public function index(): View
    {
        $scopedExamType = $this->registrarExamTypeScope();

        $schedulesQuery = ExamSchedule::withCount('applications');
        if ($scopedExamType !== null) {
            $schedulesQuery->where('exam_type', $scopedExamType);
        }

        $schedules = $schedulesQuery
            ->orderBy('exam_date')
            ->orderBy('time_slot')
            ->get();

        return view('registrar.exam-schedules.index', compact('schedules'));
    }

    /**
     * Create a new exam schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        $scopedExamType = $this->registrarExamTypeScope();

        $data = $request->validate([
            'exam_date'    => ['required', 'date', 'after_or_equal:today'],
            'time_slot'    => ['required', 'in:9am,1pm'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $data['exam_type'] = $scopedExamType ?? ExamSchedule::TYPE_JHS;

        // Prevent duplicate slot on the same date
        $exists = ExamSchedule::where('exam_date', $data['exam_date'])
            ->where('time_slot', $data['time_slot'])
            ->where('exam_type', $data['exam_type'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['exam_date' => 'A schedule for that date and time slot already exists.'])->withInput();
        }

        ExamSchedule::create($data);

        return back()->with('success', 'Exam schedule added successfully.');
    }

    /**
     * Update an existing schedule's date, capacity or active state.
     */
    public function update(Request $request, ExamSchedule $examSchedule): RedirectResponse
    {
        $scopedExamType = $this->registrarExamTypeScope();

        if ($scopedExamType !== null && $examSchedule->exam_type !== $scopedExamType) {
            abort(403, 'Unauthorized schedule scope.');
        }

        $data = $request->validate([
            'exam_date'    => ['required', 'date'],
            'time_slot'    => ['required', 'in:9am,1pm'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'is_active'    => ['boolean'],
        ]);

        $data['exam_type'] = $scopedExamType ?? $examSchedule->exam_type;

        $examSchedule->update($data);

        return back()->with('success', 'Exam schedule updated.');
    }

    /**
     * Toggle active/inactive state.
     */
    public function toggle(ExamSchedule $examSchedule): RedirectResponse
    {
        $scopedExamType = $this->registrarExamTypeScope();
        if ($scopedExamType !== null && $examSchedule->exam_type !== $scopedExamType) {
            abort(403, 'Unauthorized schedule scope.');
        }

        $examSchedule->update(['is_active' => ! $examSchedule->is_active]);

        return back()->with('success', $examSchedule->is_active ? 'Schedule activated.' : 'Schedule deactivated.');
    }

    /**
     * Delete a schedule (only if no applicants are booked).
     */
    public function destroy(ExamSchedule $examSchedule): RedirectResponse
    {
        $scopedExamType = $this->registrarExamTypeScope();
        if ($scopedExamType !== null && $examSchedule->exam_type !== $scopedExamType) {
            abort(403, 'Unauthorized schedule scope.');
        }

        if ($examSchedule->applications()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete — applicants are already booked on this schedule.']);
        }

        $examSchedule->delete();

        return back()->with('success', 'Exam schedule deleted.');
    }

    private function registrarExamTypeScope(): ?string
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return null;
        }

        if ($user->hasRole('jhs-registrar')) {
            return ExamSchedule::TYPE_JHS;
        }

        if ($user->hasRole('shs-registrar')) {
            return ExamSchedule::TYPE_SHS;
        }

        return null;
    }
}
