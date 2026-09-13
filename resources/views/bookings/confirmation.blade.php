@extends('layouts.app')

@section('title', 'Booking Confirmed — TAPGO TRAVEL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <h1 class="fw-bold mb-2">Booking Berhasil!</h1>
            <p class="text-muted mb-4">Kode booking kamu:</p>
            <p class="display-6 fw-bold text-primary mb-4">{{ $booking->booking_code }}</p>

            <div class="card border-0 shadow-sm text-start mb-4">
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $booking->schedule->trip->title }}</strong></p>
                    <p class="text-muted mb-1">{{ $booking->schedule->date->translatedFormat('d M Y') }}</p>
                    <p class="mb-1">{{ $booking->quantity }} traveler</p>
                    <p class="mb-0">Total: Rp {{ number_format($booking->total, 0, ',', '.') }}</p>
                    <span class="badge bg-warning text-dark mt-2">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>

            @if ($payment && $payment->snap_redirect_url)
                <a href="{{ $payment->snap_redirect_url }}" target="_blank" class="btn btn-primary btn-lg mb-4">
                    Bayar Sekarang (Midtrans Sandbox)
                </a>
            @elseif ($payment)
                <p class="small text-danger mb-4">
                    Link pembayaran belum tersedia (Midtrans belum dikonfigurasi di server ini).
                </p>
            @endif

            <p class="small text-muted">
                Handling payment callback &amp; verifikasi status otomatis
                akan menyusul di commit berikutnya.
            </p>
        </div>
    </div>
</div>
@endsection
