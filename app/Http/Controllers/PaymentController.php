<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans,
        protected PaymentService $paymentService,
    ) {
    }

    /**
     * Terima payment notification/callback dari Midtrans.
     * PRD section 19 flow: Payment Callback -> Verify Transaction ->
     * Update Payment -> Update Booking -> Generate Ticket.
     *
     * Route ini dikecualikan dari CSRF protection (lihat
     * bootstrap/app.php) karena request datang dari server Midtrans,
     * bukan dari browser pengguna. Keamanannya digantikan dengan
     * verifikasi signature_key di bawah.
     */
    public function handleMidtransCallback(Request $request): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');
        $transactionStatus = (string) $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        if (! $this->midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans callback: signature tidak valid', ['order_id' => $orderId]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = Booking::where('booking_code', $orderId)->first();

        if (! $booking) {
            Log::warning('Midtrans callback: booking tidak ditemukan', ['order_id' => $orderId]);

            return response()->json(['message' => 'Booking not found'], 404);
        }

        $payment = $booking->payments()->latest()->first();

        if (! $payment) {
            Log::warning('Midtrans callback: payment tidak ditemukan', ['order_id' => $orderId]);

            return response()->json(['message' => 'Payment not found'], 404);
        }

        $this->paymentService->applyMidtransStatus(
            $payment,
            $transactionStatus,
            $fraudStatus,
            $request->input('transaction_id'),
            $request->all(),
        );

        return response()->json(['message' => 'OK']);
    }

    /**
     * Verifikasi status pembayaran secara aktif ke Midtrans Status API
     * (PRD section 19 flow: "Verify Transaction"). Berguna untuk
     * tombol "Cek Status Pembayaran" manual kalau webhook callback
     * belum/tidak sampai.
     */
    public function verifyStatus(Booking $booking): \Illuminate\Http\RedirectResponse
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        $payment = $booking->payments()->latest()->first();

        if (! $payment) {
            return back()->with('status', 'Belum ada data pembayaran untuk booking ini.');
        }

        try {
            $status = $this->midtrans->getTransactionStatus($booking->booking_code);
        } catch (\Throwable $e) {
            return back()->withErrors(['payment' => 'Gagal mengambil status dari Midtrans: '.$e->getMessage()]);
        }

        $this->paymentService->applyMidtransStatus(
            $payment,
            (string) ($status['transaction_status'] ?? ''),
            $status['fraud_status'] ?? null,
            $status['transaction_id'] ?? null,
            $status,
        );

        return redirect()
            ->route('bookings.confirmation', $booking)
            ->with('status', 'Status pembayaran diperbarui: '.($status['transaction_status'] ?? 'unknown'));
    }
}
