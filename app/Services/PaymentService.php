<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

/**
 * PaymentService
 *
 * Business logic kompleks payment ditempatkan di Service Layer
 * (PRD section 29 Laravel Architecture).
 *
 * PRD section 19 Payment Requirements — flow:
 * Create Booking -> Pending Payment -> Midtrans -> Payment Callback
 * -> Verify Transaction -> Update Payment -> Update Booking -> Generate Ticket
 *
 * Integrasi Midtrans Sandbox & payment callback menyusul di commit
 * berikutnya. Service ini fokus mengelola siklus hidup record Payment
 * itu sendiri.
 */
class PaymentService
{
    /**
     * Buat record Payment berstatus pending untuk sebuah booking,
     * senilai booking->total (bukan dari input user).
     */
    public function createForBooking(Booking $booking, ?string $method = null): Payment
    {
        return $booking->payments()->create([
            'amount' => $booking->total,
            'method' => $method,
            'status' => 'pending',
        ]);
    }

    /**
     * Tandai payment sebagai berhasil dibayar, lalu update status
     * booking terkait jadi 'confirmed'.
     */
    public function markAsPaid(Payment $payment, ?string $transactionId = null, ?array $rawResponse = null): Payment
    {
        $payment->update([
            'status' => 'paid',
            'transaction_id' => $transactionId,
            'raw_response' => $rawResponse ? json_encode($rawResponse) : null,
            'paid_at' => now(),
        ]);

        $payment->booking->update(['status' => 'confirmed']);

        return $payment->fresh();
    }

    /**
     * Tandai payment gagal. Booking tetap 'pending' supaya user bisa
     * mencoba bayar ulang (retry), bukan otomatis dibatalkan.
     */
    public function markAsFailed(Payment $payment, ?array $rawResponse = null): Payment
    {
        $payment->update([
            'status' => 'failed',
            'raw_response' => $rawResponse ? json_encode($rawResponse) : null,
        ]);

        return $payment->fresh();
    }

    /**
     * Tandai payment kedaluwarsa (user tidak menyelesaikan pembayaran
     * dalam batas waktu) dan batalkan booking terkait.
     */
    public function markAsExpired(Payment $payment): Payment
    {
        $payment->update(['status' => 'expired']);
        $payment->booking->update(['status' => 'expired']);

        return $payment->fresh();
    }
}
