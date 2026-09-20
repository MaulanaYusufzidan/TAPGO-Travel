@extends('layouts.app')

@section('title', 'Pesanan Saya — TAPGO TRAVEL')

@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="Pesanan Saya" />
    <div class="page-title-row">
        <div>
            <h1>Pesanan Saya</h1>
            <p>Pantau status booking dan pembayaran perjalananmu.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($bookings->isEmpty())
        <div class="detail-panel text-center">
            <p class="lead mb-3">Belum ada pesanan.</p>
            <a href="{{ route('trips.index') }}" class="btn btn-primary">Jelajahi Trip</a>
        </div>
    @else
        <div class="vstack gap-3">
            @foreach ($bookings as $booking)
                @php $payment = $booking->payments->last(); @endphp
                <div class="detail-panel mb-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div>
                            <p class="text-muted small mb-1">No. Order: <strong>{{ $booking->booking_code }}</strong></p>
                            <h2 class="h6 fw-bold mb-1">{{ $booking->schedule->trip->title }}</h2>
                            <p class="text-muted small mb-0">
                                📅 {{ $booking->schedule->date->translatedFormat('d M Y') }}
                                · 👥 {{ $booking->quantity }} peserta
                            </p>
                        </div>
                        <div class="text-end">
                            <p class="h6 fw-bold mb-1" style="color:#17233b;">Rp {{ number_format($booking->total, 0, ',', '.') }}</p>
                            <span class="rating d-inline-block">Booking: {{ ucfirst($booking->status) }}</span>
                            <span class="rating d-inline-block" style="background:#17233b;">Payment: {{ ucfirst($payment->status ?? 'pending') }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                        <a href="{{ route('bookings.confirmation', $booking) }}" class="btn btn-outline-primary btn-sm">Detail Booking</a>
                        @if ($payment && $payment->status === 'paid')
                            <a href="{{ route('bookings.invoice', $booking) }}" class="btn btn-outline-primary btn-sm">Lihat Invoice</a>
                        @endif
                        @if ($booking->status === 'confirmed')
                            <a href="{{ route('bookings.ticket', $booking) }}" class="btn btn-primary btn-sm">Lihat E-Ticket</a>
                        @endif
                        @if ($payment && $payment->status === 'pending' && $payment->snap_redirect_url)
                            <a href="{{ $payment->snap_redirect_url }}" target="_blank" class="btn btn-warning btn-sm">Lanjutkan Pembayaran</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
</main>
@endsection
