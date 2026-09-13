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

        match (true) {
            in_array($transactionStatus, ['capture', 'settlement']) && $fraudStatus !== 'deny'
                => $this->paymentService->markAsPaid($payment, $request->input('transaction_id'), $request->all()),
            in_array($transactionStatus, ['deny', 'cancel']) || $fraudStatus === 'deny'
                => $this->paymentService->markAsFailed($payment, $request->all()),
            $transactionStatus === 'expire'
                => $this->paymentService->markAsExpired($payment),
            default => null, // 'pending' -> tidak ada perubahan, tunggu callback berikutnya
        };

        return response()->json(['message' => 'OK']);
    }
}
