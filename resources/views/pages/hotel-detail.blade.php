@extends('layouts.app')
@section('title', $hotel['name'] . ' — TAPGO Travel')
@section('meta_description', \Illuminate\Support\Str::limit($hotel['description'], 150))
@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb :links="[['label' => 'Hotels', 'url' => route('hotels.index')]]" current="{{ $hotel['name'] }}" />

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            <span class="rating mb-2 d-inline-block">★ {{ $hotel['rating'] }}</span>
            <h1 class="h3 fw-bold mb-1">{{ $hotel['name'] }}</h1>
            <p class="text-muted mb-0">⌖ {{ $hotel['location'] }}</p>
        </div>
        <div class="text-end">
            @if($hotel['discount'] > 0)<span class="price-was d-block">Rp {{ $hotel['original_price'] }}</span>@endif
            <p class="h4 fw-bold mb-1" style="color:#17233b;">Rp {{ $hotel['price'] }}</p>
            <a href="#rooms" class="btn btn-primary">Select Rooms</a>
        </div>
    </div>

    <div class="gallery-grid" style="height: 380px;">
        <div class="gallery-grid__main"><img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] }}"></div>
        <div class="gallery-grid__thumbs">
            @foreach(array_slice($hotel['gallery'], 0, 4) as $i => $img)
                <a href="{{ $img }}" target="_blank" rel="noopener"><img src="{{ $img }}" alt="{{ $hotel['name'] }} photo {{ $i + 1 }}"></a>
            @endforeach
        </div>
    </div>

    <div class="info-box-row">
        <div class="info-box"><h3>📍 Top Attractions</h3><ul>@foreach($hotel['top_attractions'] as $a)<li>{{ $a }}</li>@endforeach</ul></div>
        <div class="info-box"><h3>✈️ Nearest Airport</h3><ul>@foreach($hotel['nearest_airport'] as $a)<li>{{ $a }}</li>@endforeach</ul></div>
        <div class="info-box"><h3>☕ Cafe & Bars</h3><ul>@foreach($hotel['cafe_bars'] as $a)<li>{{ $a }}</li>@endforeach</ul></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <section class="detail-panel">
                <h2>About this property</h2>
                <p class="text-muted mb-0">{{ $hotel['description'] }}</p>
            </section>

            <section class="detail-panel" id="rooms">
                <h2>Choose your room</h2>
                @foreach($hotel['rooms'] as $room)
                    <div class="room-rate">
                        <div>
                            <strong class="d-block mb-2" style="color:#17233b;">{{ $room['name'] }}</strong>
                            <ul>
                                @foreach(explode(' · ', $room['notes']) as $note)
                                    <li class="{{ str_contains($note, 'Free') || str_contains($note, 'included') ? 'ok' : '' }}">{{ str_contains($note, 'Free') || str_contains($note, 'included') ? '✓' : '•' }} {{ $note }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="room-rate__price">
                            <strong>Rp {{ $room['price'] }}</strong>
                            <span>per night</span>
                            <a href="{{ route('contact', ['subject' => 'Room enquiry: ' . $hotel['name'] . ' — ' . $room['name']]) }}" class="btn btn-primary btn-sm mt-2">Enquire to Book</a>
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="detail-panel">
                <h2>Service & Amenities</h2>
                <div class="row g-2">
                    @foreach($hotel['amenities'] as $amenity)
                        <div class="col-6 col-md-4">✓ {{ $amenity }}</div>
                    @endforeach
                </div>
            </section>

            <section class="detail-panel">
                <h2>Nearest Services</h2>
                <div class="services-grid">
                    <div>
                        <h4>Landmarks</h4>
                        <ul>@foreach($hotel['services']['landmarks'] as $name => $dist)<li>{{ $name }} <span class="dist">{{ $dist }}</span></li>@endforeach</ul>
                    </div>
                    <div>
                        <h4>Dining</h4>
                        <ul>@foreach($hotel['services']['dining'] as $name => $dist)<li>{{ $name }} <span class="dist">{{ $dist }}</span></li>@endforeach</ul>
                    </div>
                    <div>
                        <h4>Transport</h4>
                        <ul>@foreach($hotel['services']['transport'] as $name => $dist)<li>{{ $name }} <span class="dist">{{ $dist }}</span></li>@endforeach</ul>
                    </div>
                    <div>
                        <h4>Shopping</h4>
                        <ul>@foreach($hotel['services']['shopping'] as $name => $dist)<li>{{ $name }} <span class="dist">{{ $dist }}</span></li>@endforeach</ul>
                    </div>
                </div>
            </section>

            <section class="detail-panel">
                <h2>Guest Reviews</h2>
                <div class="review-score">
                    <div class="review-score__big"><strong>{{ $hotel['rating'] }}</strong><span>{{ $hotel['rating_label'] }}</span></div>
                    <div class="review-score__bars">
                        @foreach($hotel['review_breakdown'] as $label => $score)
                            <div>{{ $label }} <strong>{{ $score }}</strong><div class="bar-track"><div class="bar-fill" style="width: {{ $score * 10 }}%"></div></div></div>
                        @endforeach
                    </div>
                </div>
                <p class="text-muted small mb-0">Based on {{ $hotel['reviews'] }} verified guest reviews.</p>
            </section>

            <section class="detail-panel">
                <h2>Frequently Asked Questions</h2>
                <div class="tapgo-accordion">
                    <details class="item" open><summary>Can I pay after check-in?</summary><p>Most rates require prepayment online, but select non-refundable and pay-at-hotel rates may be available depending on availability.</p></details>
                    <details class="item"><summary>Is airport transfer included?</summary><p>Airport shuttle availability varies by room rate — check the room details above or contact the property directly after booking.</p></details>
                    <details class="item"><summary>What is the cancellation policy?</summary><p>Free cancellation windows are shown per room rate. Non-refundable rates cannot be cancelled or changed.</p></details>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <div class="booking-card">
                <h3>Your stay</h3>
                <p class="text-muted small">{{ $hotel['name'] }} · {{ $hotel['location'] }}</p>
                <hr>
                <div class="d-flex justify-content-between mb-2"><span>From</span><strong>Rp {{ $hotel['price'] }}</strong></div>
                <p class="small text-muted">Per night, taxes and fees may apply.</p>
                <a href="#rooms" class="btn btn-primary w-100">View Room Rates</a>
            </div>
        </div>
    </div>
</div>
</main>
@endsection
