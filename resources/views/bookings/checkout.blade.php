@extends('layouts.app')

@section('title', 'Checkout — TAPGO TRAVEL')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Checkout</h1>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 fw-semibold mb-3">Trip</h2>
                    <p class="mb-1"><strong>{{ $schedule->trip->title }}</strong></p>
                    <p class="text-muted mb-1">{{ $schedule->trip->destination->name }}</p>
                    <p class="mb-0">{{ $schedule->date->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 fw-semibold mb-3">Travelers ({{ count($travelers) }})</h2>
                    <ol class="mb-0">
                        @foreach ($travelers as $traveler)
                            <li>{{ $traveler['full_name'] }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 fw-semibold mb-3">Price Breakdown</h2>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Rp {{ number_format($breakdown['unit_price'], 0, ',', '.') }} &times; {{ $breakdown['quantity'] }}</span>
                        <span>Rp {{ number_format($breakdown['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Service Fee</span>
                        <span>Rp {{ number_format($breakdown['service_fee'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Discount</span>
                        <span>- Rp {{ number_format($breakdown['discount'], 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total</span>
                        <span>Rp {{ number_format($breakdown['total'], 0, ',', '.') }}</span>
                    </div>

                    <form method="POST" action="{{ route('bookings.confirm') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
