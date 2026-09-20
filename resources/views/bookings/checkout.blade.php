@extends('layouts.app')

@section('title', 'Checkout — TAPGO TRAVEL')

@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 1080px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Tour Review</span></div>
        <div class="step-line"></div>
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Traveler Info</span></div>
        <div class="step-line"></div>
        <div class="step active"><span class="step-num">3</span><span class="step-label">Make Payment</span></div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="detail-panel">
                <h2>Trip</h2>
                <p class="mb-1 fw-bold" style="color:#17233b;">{{ $schedule->trip->title }}</p>
                <p class="text-muted mb-1">{{ $schedule->trip->destination->name }}</p>
                <p class="mb-0">📅 {{ $schedule->date->translatedFormat('d M Y') }}</p>
            </div>

            <div class="detail-panel">
                <h2>Travelers ({{ count($travelers) }})</h2>
                <div class="vstack gap-2">
                    @foreach ($travelers as $i => $traveler)
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span>{{ $i + 1 }}. {{ $traveler['full_name'] }}</span>
                            <span class="text-muted small">{{ $traveler['email'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="detail-panel mb-0">
                <h2>Good to know</h2>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2">✅ Instant confirmation after payment</li>
                    <li class="mb-2">✅ Secure checkout via Midtrans</li>
                    <li class="mb-0">ℹ️ Booking is only confirmed once payment is completed</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="booking-card">
                <h3>Reservation Summary</h3>
                <div class="d-flex justify-content-between small mb-2">
                    <span>Rp {{ number_format($breakdown['unit_price'], 0, ',', '.') }} &times; {{ $breakdown['quantity'] }}</span>
                    <span>Rp {{ number_format($breakdown['subtotal'], 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span>Service Fee</span>
                    <span>Rp {{ number_format($breakdown['service_fee'], 0, ',', '.') }}</span>
                </div>
                @if ($breakdown['discount'] > 0)
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-danger">Discount</span>
                        <span class="text-danger">- Rp {{ number_format($breakdown['discount'], 0, ',', '.') }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold mb-3">
                    <span>Total Price</span>
                    <span style="color:#1968e0;">Rp {{ number_format($breakdown['total'], 0, ',', '.') }}</span>
                </div>

                <form method="POST" action="{{ route('bookings.confirm') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">Confirm &amp; Pay ↗</button>
                </form>
            </div>
        </div>
    </div>
</div>
</main>
@endsection
