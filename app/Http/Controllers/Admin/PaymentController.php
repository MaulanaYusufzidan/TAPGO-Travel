<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    /**
     * PRD section 26 Admin Management — Payment: View / Verify.
     */
    public function index(Request $request): View
    {
        $payments = Payment::with('booking.user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($qq) use ($term) {
                    $qq->where('transaction_id', 'like', "%{$term}%")
                        ->orWhereHas('booking', fn ($b) => $b->where('booking_code', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'filters' => $request->only(['status', 'q']),
        ]);
    }

    public function show(Payment $payment): View
    {
        $payment->load('booking.user', 'booking.schedule.trip');

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verifikasi manual oleh admin — dipakai untuk kasus di luar jalur
     * otomatis (mis. webhook Midtrans tidak sampai, transfer manual).
     */
    public function verify(Payment $payment): RedirectResponse
    {
        if ($payment->status === 'paid') {
            return back()->withErrors(['status' => 'Payment ini sudah berstatus paid.']);
        }

        $this->paymentService->markAsPaid($payment, $payment->transaction_id, [
            'verified_manually_by_admin' => true,
        ]);

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('status', 'Payment berhasil diverifikasi manual, booking ikut jadi confirmed.');
    }
}
