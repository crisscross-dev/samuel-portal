<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registrar\StorePaymentRequest;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['enrollment.student.user', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return view('registrar.payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        $enrollment = null;
        if ($request->filled('enrollment_id')) {
            $enrollment = Enrollment::with('student.user')->findOrFail($request->enrollment_id);
        }

        $enrollments = Enrollment::with('student.user')
            ->whereIn('status', ['assessed', 'enrolled'])
            ->get();

        return view('registrar.payments.create', compact('enrollments', 'enrollment'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        Payment::create([
            'enrollment_id'    => $request->enrollment_id,
            'amount'           => $request->amount,
            'payment_date'     => $request->payment_date,
            'payment_method'   => $request->payment_method,
            'reference_number' => $request->reference_number,
            'status'           => 'pending',
            'remarks'          => $request->remarks,
        ]);

        return redirect()->route('registrar.payments.index')
            ->with('success', 'Payment recorded successfully. Awaiting verification.');
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
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
            'verified_by' => auth()->id(),
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

        return redirect()->route('registrar.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
