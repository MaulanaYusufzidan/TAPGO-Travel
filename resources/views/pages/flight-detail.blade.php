@extends('layouts.app')
@section('title', $offer->departureFlight->airline->name.' '.$offer->departureFlight->flight_number.' — TAPGO Travel')
@section('meta_description', 'Detail penerbangan dari '.$offer->departureFlight->origin_city.' ke '.$offer->departureFlight->destination_city.'.')
@section('content')
@php
    $dep = $offer->departureFlight;
    $ret = $offer->returnFlight;
    $finalPrice = $offer->final_price;
@endphp
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb :links="[['label' => 'Flights', 'url' => route('flights.index')]]" current="{{ $dep->origin_city }} to {{ $dep->destination_city }}" />
    <div class="row g-4">
        <div class="col-lg-8">
            <section class="detail-panel">
                <p class="eyebrow">{{ $dep->travel_class }} @if($offer->isRoundTrip()) · Round-trip @else · One-way @endif @if($offer->refundable) · Refundable @endif</p>
                <h1>{{ $dep->origin_city }} ({{ $dep->origin_code }}) {{ $offer->isRoundTrip() ? '⇄' : '→' }} {{ $dep->destination_city }} ({{ $dep->destination_code }})</h1>

                <p class="small text-muted mb-1">Departure — {{ $dep->departure_at->translatedFormat('d M Y') }}</p>
                <div class="flight-timeline">
                    <div><strong>{{ $dep->departure_at->format('H:i') }}</strong><span>{{ $dep->origin_code }}</span><small>{{ $dep->origin_city }}</small></div>
                    <div class="timeline-line"><span>{{ $dep->duration_label }}</span><i></i><small>{{ $dep->stops_label }}</small></div>
                    <div><strong>{{ $dep->arrival_at->format('H:i') }}</strong><span>{{ $dep->destination_code }}</span><small>{{ $dep->destination_city }}</small></div>
                </div>
                <div class="amenity-icons mt-3">
                    <span>{{ $dep->airline->name }} {{ $dep->flight_number }}</span>
                    @if($dep->wifi)<span>WiFi</span>@endif
                    @if($dep->meal)<span>In-flight Meal</span>@endif
                    <span>{{ $dep->baggage_kg }}kg baggage</span>
                </div>

                @if($ret)
                    <hr>
                    <p class="small text-muted mb-1">Return — {{ $ret->departure_at->translatedFormat('d M Y') }}</p>
                    <div class="flight-timeline">
                        <div><strong>{{ $ret->departure_at->format('H:i') }}</strong><span>{{ $ret->origin_code }}</span><small>{{ $ret->origin_city }}</small></div>
                        <div class="timeline-line"><span>{{ $ret->duration_label }}</span><i></i><small>{{ $ret->stops_label }}</small></div>
                        <div><strong>{{ $ret->arrival_at->format('H:i') }}</strong><span>{{ $ret->destination_code }}</span><small>{{ $ret->destination_city }}</small></div>
                    </div>
                    <div class="amenity-icons mt-3">
                        <span>{{ $ret->airline->name }} {{ $ret->flight_number }}</span>
                        @if($ret->wifi)<span>WiFi</span>@endif
                        @if($ret->meal)<span>In-flight Meal</span>@endif
                        <span>{{ $ret->baggage_kg }}kg baggage</span>
                    </div>
                @endif
            </section>

            <section class="detail-panel">
                <h2>Flight information</h2>
                <div class="info-grid">
                    <div><strong>Cabin class</strong><span>{{ $dep->travel_class }}</span></div>
                    <div><strong>Checked baggage</strong><span>{{ $dep->baggage_kg }} kg included</span></div>
                    <div><strong>Seats left</strong><span>{{ $offer->seats_available }} seats on this fare</span></div>
                    <div><strong>Check-in</strong><span>Available 24 hours before departure</span></div>
                </div>
            </section>

            @if($related->isNotEmpty())
                <section class="detail-panel mb-0">
                    <h2>Similar Flights</h2>
                    <div class="row g-3">
                        @foreach($related as $item)
                            <div class="col-md-6">
                                <a href="{{ route('flights.show', $item) }}" class="text-decoration-none">
                                    <div class="border rounded p-2 h-100" style="border-color:#e3e8ec !important; color:inherit;">
                                        <strong class="d-block small">{{ $item->departureFlight->origin_city }} → {{ $item->departureFlight->destination_city }}</strong>
                                        <span class="small text-muted">From Rp {{ number_format($item->final_price, 0, ',', '.') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        <aside class="col-lg-4">
            <div class="booking-card">
                <p class="eyebrow">Your flight</p>
                <h3>{{ $dep->origin_code }} → {{ $dep->destination_code }}</h3>
                <p class="text-muted small">{{ $dep->airline->name }} {{ $dep->flight_number }} · per traveler</p>
                <hr>
                @if($offer->discount_percentage)
                    <div class="d-flex justify-content-between text-muted small mb-1"><span>Was</span><span class="price-was">Rp {{ number_format($offer->base_price, 0, ',', '.') }}</span></div>
                @endif
                <div class="d-flex justify-content-between mb-3"><span>Price</span><strong>Rp {{ number_format($finalPrice, 0, ',', '.') }}</strong></div>

                @auth
                    <form method="POST" action="{{ route('flight-bookings.store') }}">
                        @csrf
                        <input type="hidden" name="flight_offer_id" value="{{ $offer->id }}">
                        <label class="small text-muted">Passengers</label>
                        <select name="passengers" class="form-select form-select-sm mb-3">
                            @for($i = 1; $i <= min(9, $offer->seats_available); $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'passenger' : 'passengers' }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary w-100">Select Flight ↗</button>
                    </form>
                @else
                    <p class="small text-muted">Please <a href="{{ route('login') }}">login</a> to book this flight.</p>
                @endauth
            </div>
        </aside>
    </div>
</div>
</main>
@endsection
