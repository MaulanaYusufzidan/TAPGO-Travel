@extends('layouts.app')
@section('title', 'Payment — TAPGO Travel')
@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 640px;">
    <div class="booking-card">
        <h3>Payment</h3>
        <p class="text-muted small mb-3">Kode booking: <strong>{{ $booking->booking_code }}</strong></p>

        <div class="d-flex justify-content-between mb-2"><span>Jumlah</span><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
        <div class="d-flex justify-content-between mb-2"><span>Metode</span><span>{{ str($payment->method)->headline() }}</span></div>
        <div class="d-flex justify-content-between mb-2"><span>Status</span><span class="rating">{{ ucfirst($payment->status) }}</span></div>
        <div class="d-flex justify-content-between mb-3"><span>Batas waktu bayar</span><span>{{ $payment->expired_at?->translatedFormat('d M Y, H:i') }}</span></div>

        @if($payment->status === 'paid')
            <div class="alert alert-success small mb-0">Pembayaran berhasil. <a href="{{ route('hotel-bookings.confirmation', $booking) }}">Lihat konfirmasi booking</a>.</div>
        @else
            @if($payment->status === 'failed')
                <div class="alert alert-danger small">Pembayaran gagal. Silakan coba lagi.</div>
            @endif

            @unless(app()->environment('production'))
                <p class="small text-muted">Tombol simulasi ini cuma buat development/demo — belum terhubung payment gateway asli.</p>
                <form method="POST" action="{{ route('hotel-bookings.payment.simulate', $booking) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="result" value="success">
                    <button type="submit" class="btn btn-success btn-sm">Simulate Successful Payment</button>
                </form>
                <form method="POST" action="{{ route('hotel-bookings.payment.simulate', $booking) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="result" value="failed">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Simulate Failed Payment</button>
                </form>
            @endunless
        @endif
    </div>
</div>
</main>
@endsection
