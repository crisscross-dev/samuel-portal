<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPayment;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionPaymentController extends Controller
{
    /**
     * Show the GCash payment page for a given Application ID.
     *
     * To change GCash details, update these in your .env file:
     *   GCASH_NUMBER   — school's GCash mobile number
     *   GCASH_NAME     — account name
     *   GCASH_FEE      — admission fee amount
     *   GCASH_QR_IMAGE — path to the GCash QR image (relative to public/)
     *                    Download it from GCash app → More → My QR Code → Save
     */
    public function show(string $appId): View
    {
        $application = Application::where('app_id', $appId)
            ->with(['program', 'admissionPayment'])
            ->firstOrFail();

        $gcashNumber  = config('gcash.number');
        $gcashName    = config('gcash.name');
        $gcashFee     = config('gcash.fee');
        $gcashQrImage = config('gcash.qr_image');
        $gcashQrExists = file_exists(public_path($gcashQrImage));

        return view('admission.payment', compact(
            'application',
            'gcashNumber',
            'gcashName',
            'gcashFee',
            'gcashQrImage',
            'gcashQrExists'
        ));
    }

    /**
     * Save the student's GCash payment submission (reference no. + receipt).
     */
    public function store(Request $request, string $appId): RedirectResponse
    {
        $application = Application::where('app_id', $appId)->firstOrFail();

        // Prevent duplicate submission if already paid
        if ($application->isPaymentPaid()) {
            return redirect()->route('admission.payment.show', $appId)
                ->with('info', 'Your payment has already been verified.');
        }

        $data = $request->validate([
            'reference_number' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9\-]+$/'],
            'receipt_image'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'reference_number.regex' => 'Reference number may only contain letters, numbers, and hyphens.',
        ]);

        $data['receipt_image'] = $request->file('receipt_image')
            ->store('admission_receipts', 'public');

        $application->admissionPayment()->updateOrCreate(
            ['application_id' => $application->id],
            array_merge($data, [
                'payment_status' => 'pending',
                'submitted_at'   => now(),
            ])
        );

        $application->update(['payment_status' => 'pending']);

        $application->refresh();

        return redirect()->route('admission.success')
            ->with('app_id', $appId)
            ->with('exam_schedule', $application->exam_schedule)
            ->with('payment_submitted', true);
    }
}
