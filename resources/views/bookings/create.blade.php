@extends('layouts.app')

@section('title', 'Traveler Information — TAPGO TRAVEL')

@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 960px;">
    <div class="booking-stepper">
        <div class="step done"><span class="step-num">✓</span><span class="step-label">Tour Review</span></div>
        <div class="step-line"></div>
        <div class="step active"><span class="step-num">2</span><span class="step-label">Traveler Info</span></div>
        <div class="step-line"></div>
        <div class="step"><span class="step-num">3</span><span class="step-label">Make Payment</span></div>
    </div>

    <div class="detail-panel mb-4 d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div>
            <p class="eyebrow mb-1">Your trip</p>
            <h1 class="h4 fw-bold mb-1">{{ $schedule->trip->title }}</h1>
            <p class="text-muted mb-0">📅 {{ $schedule->date->translatedFormat('d M Y') }} · 👥 {{ $quantity }} {{ $quantity > 1 ? 'travelers' : 'traveler' }}</p>
        </div>
        @if ($schedule->trip->base_price)
            <div class="text-end">
                <p class="small text-muted mb-0">Price / person</p>
                <p class="h5 fw-bold mb-0" style="color:#17233b;">Rp {{ number_format($schedule->price ?? $schedule->trip->base_price, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

        @for ($i = 0; $i < $quantity; $i++)
            <div class="detail-panel">
                <h2 class="h6 fw-bold text-uppercase mb-3">Traveler {{ $i + 1 }}</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">Full Name</label>
                        <input type="text" name="travelers[{{ $i }}][full_name]" class="form-control" placeholder="As per ID / passport" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Gender</label>
                        <select name="travelers[{{ $i }}][gender]" class="form-select">
                            <option value="">-</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Date of Birth</label>
                        <input type="date" name="travelers[{{ $i }}][date_of_birth]" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Phone Number</label>
                        <input type="text" name="travelers[{{ $i }}][phone]" class="form-control" placeholder="+62...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Email</label>
                        <input type="email" name="travelers[{{ $i }}][email]" class="form-control" placeholder="name@example.com">
                    </div>
                </div>
            </div>
        @endfor

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">Continue to Payment →</button>
        </div>
    </form>
</div>
</main>
@endsection
