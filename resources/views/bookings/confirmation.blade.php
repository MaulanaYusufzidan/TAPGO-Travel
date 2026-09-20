@extends('layouts.app')

@section('title', 'Booking Confirmed — TAPGO TRAVEL')

@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 720px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Tour Review</span></div>
        <div class="step-line"></div>
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Traveler Info</span></div>
        <div class="step-line"></div>
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Payment</span></div>
    </div>

    <div class="text-center">
        <p class="display-6 mb-2">✅</p>
        <h1 class="fw-bold mb-2">Booking Berhasil!</h1>
        <p class="text-muted mb-3">Kode booking kamu:</p>
        <p class="display-6 fw-bold mb-4" style="color:#1968e0;">{{ $booking->booking_code }}</p>
    </div>

    <div class="detail-panel text-start mb-4">
        <p class="mb-1 fw-bold" style="color:#17233b;">{{ $booking->schedule->trip->title }}</p>
        <p class="text-muted mb-1">{{ $booking->schedule->date->translatedFormat('d M Y') }}</p>
        <p class="mb-1">{{ $booking->quantity }} traveler</p>
        <p class="mb-2">Total: <strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong></p>
        <span class="rating">{{ ucfirst($booking->status) }}</span>
    </div>

    <div class="text-center">
        @if ($payment && $payment->snap_redirect_url)
            <a href="{{ $payment->snap_redirect_url }}" target="_blank" class="btn btn-primary btn-lg mb-3">
                Bayar Sekarang (Midtrans Sandbox)
            </a>
        @elseif ($payment)
            <p class="small text-danger mb-3">
                Link pembayaran belum tersedia (Midtrans belum dikonfigurasi di server ini).
            </p>
        @endif

        @if ($payment && in_array($payment->status, ['pending']))
            <form method="POST" action="{{ route('payments.verify', $booking) }}" class="mb-4">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    Cek Status Pembayaran
                </button>
            </form>
        @endif

        @if ($booking->status === 'confirmed')
            <a href="{{ route('bookings.ticket', $booking) }}" class="btn btn-primary mb-4">
                Lihat E-Ticket
            </a>
        @endif

        <p class="small text-muted">
            Status pembayaran diperbarui otomatis lewat callback Midtrans,
            atau bisa dicek manual lewat tombol di atas.
        </p>
    </div>
</div>
</main>
@endsection
