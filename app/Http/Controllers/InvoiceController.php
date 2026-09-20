<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvoiceController extends Controller
{
    /**
     * Invoice dibangun langsung dari data Booking + Payment yang sudah
     * ada (bukan tabel baru — datanya identik, jadi tabel `invoices`
     * terpisah hanya akan menduplikasi data). Nomor invoice memakai
     * booking_code yang sudah unik per booking.
     *
     * Halaman ini bisa "didownload" sebagai PDF lewat print dialog
     * browser (window.print, lihat tombol di view) karena environment
     * ini tidak bisa mengakses Packagist untuk memasang library PDF
     * server-side (barryvdh/laravel-dompdf dsb). Kalau nanti mau PDF
     * yang digenerate di server, tinggal composer require paket itu
     * lalu ganti tombol Download di view ini.
     */
    public function show(Booking $booking): View|RedirectResponse
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $booking->load(['schedule.trip.destination', 'travelers', 'payments']);

        $payment = $booking->payments->firstWhere('status', 'paid') ?? $booking->payments->last();

        if (! $payment || $payment->status !== 'paid') {
            return redirect()
                ->route('bookings.confirmation', $booking)
                ->with('status', 'Invoice hanya tersedia setelah pembayaran berhasil (lunas).');
        }

        return view('bookings.invoice', [
            'booking' => $booking,
            'payment' => $payment,
        ]);
    }
}
