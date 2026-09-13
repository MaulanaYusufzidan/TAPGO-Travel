<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * MidtransService
 *
 * Integrasi dengan Midtrans Snap API (Sandbox), sesuai PRD:
 * "Payment menggunakan: Midtrans Sandbox".
 *
 * Ditulis pakai Laravel HTTP client langsung (bukan SDK resmi
 * midtrans/midtrans-php) karena environment build ini tidak punya
 * akses ke Packagist untuk composer require paket pihak ketiga.
 * Endpoint & payload mengikuti dokumentasi resmi Snap API Midtrans.
 */
class MidtransService
{
    protected string $serverKey;

    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key');
        $this->isProduction = (bool) config('services.midtrans.is_production');
    }

    protected function snapBaseUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Buat Snap transaction untuk sebuah booking. Mengembalikan
     * ['token' => ..., 'redirect_url' => ...] dari Midtrans, supaya
     * frontend bisa redirect/membuka Snap popup.
     *
     * @throws \RuntimeException kalau request ke Midtrans gagal
     */
    public function createSnapTransaction(Booking $booking): array
    {
        $booking->loadMissing(['user', 'schedule.trip']);

        $payload = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) round($booking->total),
            ],
            'customer_details' => [
                'first_name' => $booking->user->name ?? 'Traveler',
                'email' => $booking->user->email ?? null,
            ],
            'item_details' => [
                [
                    'id' => (string) $booking->schedule_id,
                    'price' => (int) round($booking->price),
                    'quantity' => $booking->quantity,
                    'name' => str($booking->schedule->trip->title ?? 'TAPGO TRAVEL Trip')->limit(50, ''),
                ],
            ],
        ];

        $response = Http::withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->contentType('application/json')
            ->post($this->snapBaseUrl().'/transactions', $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap transaction failed', [
                'booking_id' => $booking->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Gagal membuat transaksi Midtrans: '.$response->body());
        }

        return [
            'token' => $response->json('token'),
            'redirect_url' => $response->json('redirect_url'),
        ];
    }
}
