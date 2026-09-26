@extends('layouts.app')
@section('title', 'Flight Checkout — TAPGO Travel')
@section('content')
@php
    $dep = $offer->departureFlight;
    $ret = $offer->returnFlight;
@endphp
<main class="marketplace-page">
<div class="container" style="max-width: 1080px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Select Flight</span></div>
        <div class="step-line"></div>
        <div class="step active"><span class="step-num">2</span><span class="step-label">Traveler Details & Payment</span></div>
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

    <form method="POST" action="{{ route('flight-bookings.confirm') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="detail-panel">
                    <h2>Your Flight</h2>
                    <p class="mb-1 fw-bold" style="color:#17233b;">{{ $dep->origin_city }} ({{ $dep->origin_code }}) {{ $ret ? '⇄' : '→' }} {{ $dep->destination_city }} ({{ $dep->destination_code }})</p>
                    <p class="text-muted mb-1">{{ $dep->airline->name }} {{ $dep->flight_number }} · {{ $dep->travel_class }} · {{ $passengers }} passenger(s)</p>
                    <p class="mb-0">🛫 Departure {{ $dep->departure_at->translatedFormat('d M Y, H:i') }}</p>
                    @if($ret)<p class="mb-0">🛬 Return {{ $ret->departure_at->translatedFormat('d M Y, H:i') }}</p>@endif
                </div>

                <p class="small text-info bg-info-subtle rounded p-2 mb-3">
                    <em>New:</em> Please enter names exactly as they appear on your passport.
                </p>

                @for($i = 0; $i < $passengers; $i++)
                    <div class="detail-panel">
                        <h2>Traveler {{ $i + 1 }}</h2>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="small text-muted">First Name</label>
                                <input type="text" name="passengers[{{ $i }}][first_name]" class="form-control" value="{{ old('passengers.'.$i.'.first_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Last Name</label>
                                <input type="text" name="passengers[{{ $i }}][last_name]" class="form-control" value="{{ old('passengers.'.$i.'.last_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Passport Number</label>
                                <input type="text" name="passengers[{{ $i }}][passport_number]" class="form-control" value="{{ old('passengers.'.$i.'.passport_number') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Passport Expiry</label>
                                <input type="date" name="passengers[{{ $i }}][passport_expiry]" class="form-control" value="{{ old('passengers.'.$i.'.passport_expiry') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Date of Birth</label>
                                <input type="date" name="passengers[{{ $i }}][date_of_birth]" class="form-control" value="{{ old('passengers.'.$i.'.date_of_birth') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Nationality</label>
                                <input type="text" name="passengers[{{ $i }}][nationality]" class="form-control" value="{{ old('passengers.'.$i.'.nationality', 'Indonesian') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted d-block">Gender</label>
                                <label class="me-3"><input type="radio" name="passengers[{{ $i }}][gender]" value="male" @checked(old('passengers.'.$i.'.gender') === 'male') required> Male</label>
                                <label><input type="radio" name="passengers[{{ $i }}][gender]" value="female" @checked(old('passengers.'.$i.'.gender') === 'female')> Female</label>
                            </div>
                        </div>
                    </div>
                @endfor

                <div class="detail-panel">
                    <h2>Personal Information</h2>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="small text-muted">Email Address</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Mobile Number</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}" required>
                        </div>
                    </div>
                </div>

                <div class="detail-panel mb-0">
                    <h2>Payment Method</h2>
                    <div class="row g-2">
                        @php
                            $paymentMethods = ['qris' => 'qris', 'bank_transfer' => 'bank', 'e_wallet' => 'wallet', 'credit_card' => 'card'];
                            $paymentLabels = ['qris' => 'QRIS', 'bank_transfer' => 'Bank Transfer', 'e_wallet' => 'E-Wallet', 'credit_card' => 'Credit / Debit Card'];
                        @endphp
                        @foreach($paymentMethods as $value => $icon)
                            <div class="col-6 col-md-3">
                                <label class="payment-method-option" style="display:block; border:1px solid #e9edf0; border-radius:.6rem; padding:.75rem; text-align:center; cursor:pointer;">
                                    <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method') === $value) required style="display:block; margin: 0 auto .4rem;">
                                    @switch($icon)
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
                                    <span class="small mt-1 d-block">{{ $paymentLabels[$value] }}</span>
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
                        <span>Base Fare ({{ $passengers }}x)</span>
                        <span>Rp {{ number_format($breakdown['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Tax</span>
                        <span>Rp {{ number_format($breakdown['tax'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Other Services</span>
                        <span>Rp {{ number_format($breakdown['service_fee'], 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total Price</span>
                        <span style="color:#1968e0;">Rp {{ number_format($breakdown['total'], 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <label class="small text-muted">Have a Coupon Code?</label>
                    <div class="d-flex gap-2 mb-3">
                        <input type="text" class="form-control form-control-sm" placeholder="Enter code" disabled>
                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Apply</button>
                    </div>
                    <p class="small text-muted mb-3"><em>Coupon belum aktif — placeholder buat fase selanjutnya.</em></p>
                    <button type="submit" class="btn btn-primary w-100">Submit & Proceed for Payment</button>
                </div>
            </div>
        </div>
    </form>
</div>
</main>
@endsection
