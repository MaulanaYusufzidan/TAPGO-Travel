@extends('layouts.app')
@section('title', 'Booking Confirmed — TAPGO Travel')
@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 720px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Select Room</span></div>
        <div class="step-line"></div>
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Guest Info & Payment</span></div>
        <div class="step-line"></div>
        <div class="step active"><span class="step-num">3</span><span class="step-label">Confirmation</span></div>
    </div>

    <div class="text-center">
        <p class="display-6 mb-2">✅</p>
        <h1 class="fw-bold mb-2">Booking Diterima!</h1>
        <p class="text-muted mb-3">Kode booking kamu:</p>
        <p class="display-6 fw-bold mb-4" style="color:#1968e0;">{{ $booking->booking_code }}</p>
    </div>

    <div class="detail-panel text-start mb-4">
        <p class="mb-1 fw-bold" style="color:#17233b;">{{ $booking->hotel->name }}</p>
        <p class="text-muted mb-1">{{ $booking->hotel->city }}, {{ $booking->hotel->province }}</p>
        <p class="mb-1">📅 {{ $booking->check_in->translatedFormat('d M Y') }} — {{ $booking->check_out->translatedFormat('d M Y') }}</p>
        @foreach($booking->bookingRooms as $room)
            <p class="mb-1">{{ $room->quantity }}x {{ $room->roomType->name ?? 'Room' }}{{ $room->ratePlan ? ' ('.$room->ratePlan->name.')' : '' }} · {{ $room->nights }} night(s)</p>
        @endforeach
        <p class="mb-2">Total: <strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong></p>
        <span class="rating">{{ ucfirst($booking->status) }}</span>
    </div>

    @if($booking->status === 'confirmed')
        <div class="alert alert-success small text-start">Pembayaran berhasil. Booking kamu terkonfirmasi.</div>
    @else
        <div class="alert alert-info small text-start">
            Kamar kamu sudah kami tahan. <a href="{{ route('hotel-bookings.payment', $booking) }}">Lanjut ke pembayaran</a>.
        </div>
    @endif
</div>
</main>
@endsection
