<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registrar\StorePaymentRequest;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\AssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private AssessmentService $assessmentService,
    ) {}

    private function routeBase(): string
    {
        $routeName = request()->route()?->getName() ?? '';

        return str_starts_with($routeName, 'cashier.') ? 'cashier.' : 'registrar.';
    }

    private function paymentsQuery(Request $request)
    {
        $query = Payment::with(['enrollment.student.user', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $readyForPaymentEnrollments = Enrollment::with(['student.user', 'semester.academicYear', 'payments'])
            ->whereIn('status', ['pending', 'assessed', 'enrolled'])
            ->latest()
            ->get()
            ->map(function (Enrollment $enrollment) {
                $breakdown = $this->assessmentService->getBreakdown($enrollment);

                $enrollment->payment_total = $breakdown['total'] ?? (float) $enrollment->total_amount;
                $enrollment->payment_balance = $breakdown['balance'] ?? $enrollment->balance();

                return $enrollment;
            });

        $routeBase = $this->routeBase();
        $showPaymentHistory = $routeBase !== 'cashier.';
        $payments = $showPaymentHistory
            ? $this->paymentsQuery($request)->latest()->paginate(15)
            : null;

        return view('registrar.payments.index', compact('payments', 'readyForPaymentEnrollments', 'routeBase', 'showPaymentHistory'));
    }

    public function logs(Request $request): View
    {
        $payments = $this->paymentsQuery($request)->latest()->paginate(15)->withQueryString();
        $routeBase = $this->routeBase();

        return view('registrar.payments.logs', compact('payments', 'routeBase'));
    }

    public function create(Request $request): View
    {
        $enrollment = null;
        $breakdown = [];
        $pageError = null;

        if ($request->filled('enrollment_id')) {
            $enrollment = Enrollment::with([
                'student.user',
                'student.program',
                'student.section.gradeLevel.department',
                'semester.academicYear',
                'enrollmentSubjects.subject',
                'payments',
                'tuitionStructure.department',
                'tuitionStructure.academicYear',
            ])->findOrFail($request->enrollment_id);

            try {
                $breakdown = $this->assessmentService->getBreakdown($enrollment);
            } catch (\Throwable $e) {
                $pageError = $e->getMessage();
            }
        }

        $enrollments = Enrollment::with(['student.user', 'semester.academicYear', 'payments'])
            ->whereIn('status', ['pending', 'assessed', 'enrolled'])
            ->get()
            ->map(function (Enrollment $listedEnrollment) {
                $breakdown = $this->assessmentService->getBreakdown($listedEnrollment);

                $listedEnrollment->payment_total = $breakdown['total'] ?? (float) $listedEnrollment->total_amount;
                $listedEnrollment->payment_balance = $breakdown['balance'] ?? $listedEnrollment->balance();

                return $listedEnrollment;
            });

        $routeBase = $this->routeBase();

        return view('registrar.payments.create', compact('enrollments', 'enrollment', 'breakdown', 'pageError', 'routeBase'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        $isCashierRoute = $this->routeBase() === 'cashier.';

        if ((float) $enrollment->total_amount <= 0 || $enrollment->status === 'pending') {
            $this->assessmentService->generateAssessment($enrollment);
            $enrollment->refresh();
        }

        Payment::create([
            'enrollment_id'    => $request->enrollment_id,
            'amount'           => $request->amount,
            'payment_date'     => $request->payment_date,
            'payment_method'   => $request->payment_method,
            'reference_number' => $request->reference_number,
            'status'           => $isCashierRoute ? 'verified' : 'pending',
            'verified_by'      => $isCashierRoute ? Auth::id() : null,
            'verified_at'      => $isCashierRoute ? now() : null,
            'remarks'          => $request->remarks,
        ]);

        return redirect()->route($this->routeBase() . 'payments.index')
            ->with('success', $isCashierRoute
                ? 'Payment recorded and verified successfully.'
                : 'Payment recorded successfully. Awaiting verification.');
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status'      => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a payment.
     */
    public function reject(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status'      => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Payment rejected.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        if ($payment->status === 'verified') {
            return back()->with('error', 'Cannot delete a verified payment.');
        }

        $payment->delete();

        return redirect()->route($this->routeBase() . 'payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
