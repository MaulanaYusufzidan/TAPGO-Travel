@extends('layouts.app')
@section('title', 'Hotel Checkout — TAPGO Travel')
@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 1080px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Select Room</span></div>
        <div class="step-line"></div>
        <div class="step active"><span class="step-num">2</span><span class="step-label">Guest Info & Payment</span></div>
        <div class="step-line"></div>
        <div class="step"><span class="step-num">3</span><span class="step-label">Confirmation</span></div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('hotel-bookings.confirm') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="detail-panel">
                    <h2>Your Stay</h2>
                    <p class="mb-1 fw-bold" style="color:#17233b;">{{ $hotel->name }}</p>
                    <p class="text-muted mb-1">{{ $roomType->name }} · {{ $quantity }} room(s) · {{ $guests }} guests</p>
                    <p class="mb-0">📅 {{ $checkIn->translatedFormat('d M Y') }} — {{ $checkOut->translatedFormat('d M Y') }} ({{ $breakdown['nights'] }} night{{ $breakdown['nights'] > 1 ? 's' : '' }})</p>
                </div>

                <div class="detail-panel">
                    <h2>Guest Information</h2>
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="small text-muted">Full Name</label>
                            <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name', auth()->user()->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Email</label>
                            <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email', auth()->user()->email ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Phone</label>
                            <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="small text-muted">Special Request (optional)</label>
                            <textarea name="special_request" class="form-control" rows="2">{{ old('special_request') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="detail-panel mb-0">
                    <h2>Payment Method</h2>
                    <div class="vstack gap-2">
                        @foreach(['qris' => 'QRIS', 'bank_transfer' => 'Bank Transfer', 'e_wallet' => 'E-Wallet', 'credit_card' => 'Credit Card'] as $value => $label)
                            <label class="d-flex align-items-center gap-2">
                                <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method') === $value) required>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="booking-card">
                    <h3>Price Summary</h3>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Room</span>
                        <span>Rp {{ number_format($breakdown['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Tax</span>
                        <span>Rp {{ number_format($breakdown['tax'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Service fee</span>
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
                        <span>Total</span>
                        <span style="color:#1968e0;">Rp {{ number_format($breakdown['total'], 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reserve Now</button>
                </div>
            </div>
        </div>
    </form>
</div>
</main>
@endsection
