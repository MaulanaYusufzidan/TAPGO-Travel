@extends('layouts.app')
@section('title', 'Booking Confirmed — TAPGO Travel')
@section('content')
@php $dep = $booking->flightOffer->departureFlight; $ret = $booking->flightOffer->returnFlight; @endphp
<main class="marketplace-page">
<div class="container" style="max-width: 720px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Select Flight</span></div>
        <div class="step-line"></div>
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Traveler Details & Payment</span></div>
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
        <p class="mb-1 fw-bold" style="color:#17233b;">{{ $dep->origin_city }} ({{ $dep->origin_code }}) {{ $ret ? '⇄' : '→' }} {{ $dep->destination_city }} ({{ $dep->destination_code }})</p>
        <p class="text-muted mb-1">{{ $dep->airline->name }} {{ $dep->flight_number }} · {{ $dep->travel_class }}</p>
        <p class="mb-1">🛫 {{ $dep->departure_at->translatedFormat('d M Y, H:i') }}</p>
        @if($ret)<p class="mb-1">🛬 Return {{ $ret->departure_at->translatedFormat('d M Y, H:i') }}</p>@endif

        <p class="mb-1 mt-2 fw-semibold small">Travelers:</p>
        @foreach($booking->passengerDetails as $p)
            <p class="mb-1 small">{{ $p->full_name }} — {{ $p->passport_number }}</p>
        @endforeach

        <p class="mb-2 mt-2">Total: <strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong></p>
        <span class="rating">{{ ucfirst($booking->status) }}</span>
    </div>

    @if($booking->status === 'confirmed')
        <div class="alert alert-success small text-start">Pembayaran berhasil. Booking kamu terkonfirmasi.</div>
    @else
        <div class="alert alert-info small text-start">
            Kursi kamu sudah kami tahan. <a href="{{ route('flight-bookings.payment', $booking) }}">Lanjut ke pembayaran</a>.
        </div>
    @endif
</div>
</main>
@endsection
