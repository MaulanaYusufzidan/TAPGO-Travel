<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

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
     * senilai booking->total (bukan dari input user). Kalau
     * MidtransService disediakan, langsung buat Snap transaction dan
     * simpan token + redirect_url-nya.
     */
    public function createForBooking(Booking $booking, ?string $method = null, ?MidtransService $midtrans = null): Payment
    {
        $payment = $booking->payments()->create([
            'amount' => $booking->total,
            'method' => $method,
            'status' => 'pending',
        ]);

        if ($midtrans) {
            try {
                $snap = $midtrans->createSnapTransaction($booking);
                $payment->update([
                    'snap_token' => $snap['token'] ?? null,
                    'snap_redirect_url' => $snap['redirect_url'] ?? null,
                ]);
            } catch (\Throwable $e) {
                Log::warning('Gagal membuat Snap transaction, payment tetap dibuat tanpa snap_token', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $payment->fresh();
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

    /**
     * Terapkan transaction_status dari Midtrans (baik dari webhook
     * callback maupun dari Status API aktif) ke sebuah Payment,
     * memakai mapping yang sama supaya kedua jalur konsisten.
     */
    public function applyMidtransStatus(
        Payment $payment,
        string $transactionStatus,
        ?string $fraudStatus = null,
        ?string $transactionId = null,
        ?array $rawResponse = null,
    ): Payment {
        return match (true) {
            in_array($transactionStatus, ['capture', 'settlement']) && $fraudStatus !== 'deny'
                => $this->markAsPaid($payment, $transactionId, $rawResponse),
            in_array($transactionStatus, ['deny', 'cancel']) || $fraudStatus === 'deny'
                => $this->markAsFailed($payment, $rawResponse),
            $transactionStatus === 'expire'
                => $this->markAsExpired($payment),
            default => $payment, // 'pending' -> tidak ada perubahan
        };
    }
}
