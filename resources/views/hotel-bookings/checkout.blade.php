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
                    <div class="row g-2">
                        @php
                            $paymentMethods = [
                                'qris' => ['label' => 'QRIS', 'icon' => 'qris'],
                                'bank_transfer' => ['label' => 'Bank Transfer', 'icon' => 'bank'],
                                'e_wallet' => ['label' => 'E-Wallet', 'icon' => 'wallet'],
                                'credit_card' => ['label' => 'Credit / Debit Card', 'icon' => 'card'],
                            ];
                        @endphp
                        @foreach($paymentMethods as $value => $method)
                            <div class="col-6 col-md-3">
                                <label class="payment-method-option" style="display:block; border:1px solid #e9edf0; border-radius:.6rem; padding:.75rem; text-align:center; cursor:pointer;">
                                    <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method') === $value) required style="display:block; margin: 0 auto .4rem;">
                                    @switch($method['icon'])
                                        @case('qris')
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" style="margin:0 auto;display:block;"><rect x="2" y="2" width="7" height="7" rx="1" stroke="#17233b" stroke-width="1.6"/><rect x="15" y="2" width="7" height="7" rx="1" stroke="#17233b" stroke-width="1.6"/><rect x="2" y="15" width="7" height="7" rx="1" stroke="#17233b" stroke-width="1.6"/><rect x="4.3" y="4.3" width="2.4" height="2.4" fill="#17233b"/><rect x="17.3" y="4.3" width="2.4" height="2.4" fill="#17233b"/><rect x="4.3" y="17.3" width="2.4" height="2.4" fill="#17233b"/><rect x="15" y="15" width="3" height="3" fill="#17233b"/><rect x="19" y="15" width="3" height="3" fill="#17233b"/><rect x="15" y="19" width="3" height="3" fill="#17233b"/><rect x="19" y="19" width="3" height="3" fill="#17233b"/></svg>
                                            @break
                                        @case('bank')
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" style="margin:0 auto;display:block;"><path d="M2 9L12 3l10 6" stroke="#17233b" stroke-width="1.6" stroke-linejoin="round"/><rect x="4" y="9" width="16" height="10" stroke="#17233b" stroke-width="1.6"/><path d="M2 21h20" stroke="#17233b" stroke-width="1.6"/><path d="M7 12v4M12 12v4M17 12v4" stroke="#17233b" stroke-width="1.6"/></svg>
                                            @break
                                        @case('wallet')
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" style="margin:0 auto;display:block;"><rect x="2" y="6" width="20" height="14" rx="2" stroke="#17233b" stroke-width="1.6"/><path d="M2 10h20" stroke="#17233b" stroke-width="1.6"/><circle cx="17" cy="14.5" r="1.4" fill="#17233b"/></svg>
                                            @break
                                        @case('card')
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" style="margin:0 auto;display:block;"><rect x="2" y="5" width="20" height="14" rx="2" stroke="#17233b" stroke-width="1.6"/><path d="M2 10h20" stroke="#17233b" stroke-width="1.6"/><path d="M5 15h6" stroke="#17233b" stroke-width="1.6"/></svg>
                                            @break
                                    @endswitch
                                    <span class="small mt-1 d-block">{{ $method['label'] }}</span>
                                </label>
                            </div>
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
