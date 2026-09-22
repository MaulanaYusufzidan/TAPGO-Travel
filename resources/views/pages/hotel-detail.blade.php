@extends('layouts.app')
@section('title', $hotel->name . ' — TAPGO Travel')
@section('meta_description', \Illuminate\Support\Str::limit($hotel->description, 150))
@section('content')
@php
    $fallback = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85';
    $mainImage = $hotel->images->firstWhere('is_primary', true) ?? $hotel->images->first();
    $thumbs = $hotel->images->reject(fn ($img) => $img->id === ($mainImage->id ?? null))->take(4);
    $nearbyGrouped = $hotel->nearbyPlaces->groupBy('category');
    $ratingLabel = match (true) {
        $hotel->rating_avg >= 9 => 'Exceptional',
        $hotel->rating_avg >= 8 => 'Excellent',
        $hotel->rating_avg >= 7 => 'Very Good',
        $hotel->rating_avg > 0 => 'Good',
        default => 'Not yet rated',
    };
@endphp
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb :links="[['label' => 'Hotels', 'url' => route('hotels.index')]]" current="{{ $hotel->name }}" />

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            @if($hotel->rating_avg > 0)
                <span class="rating mb-2 d-inline-block">★ {{ number_format($hotel->rating_avg, 1) }}</span>
            @endif
            <h1 class="h3 fw-bold mb-1">{{ $hotel->name }}</h1>
            <p class="text-muted mb-0">⌖ {{ $hotel->address ? $hotel->address.', ' : '' }}{{ $hotel->city }}, {{ $hotel->province }}</p>
        </div>
        <div class="text-end">
            @if($fromPrice)
                <p class="small text-muted mb-1">From</p>
                <p class="h4 fw-bold mb-1" style="color:#17233b;">Rp {{ number_format($fromPrice, 0, ',', '.') }}</p>
                <span class="small text-muted d-block mb-2">per night</span>
            @endif
            <a href="#rooms" class="btn btn-primary">Select Rooms</a>
        </div>
    </div>

    <div class="gallery-grid" style="height: 380px;">
        <div class="gallery-grid__main">
            <img src="{{ $mainImage->image_path ?? $fallback }}" alt="{{ $hotel->name }}" onerror="this.onerror=null;this.src='{{ $fallback }}'">
        </div>
        <div class="gallery-grid__thumbs">
            @foreach($thumbs as $i => $img)
                <a href="{{ $img->image_path }}" target="_blank" rel="noopener"><img src="{{ $img->image_path }}" alt="{{ $hotel->name }} photo {{ $i + 1 }}"></a>
            @endforeach
        </div>
    </div>

    <div class="info-box-row">
        <div class="info-box"><h3>📍 Top Attractions</h3><ul>@forelse($nearbyGrouped->get('Attraction', collect()) as $a)<li>{{ $a->name }} <span class="dist">{{ $a->distance }} {{ $a->unit }}</span></li>@empty<li class="text-muted">No data yet</li>@endforelse</ul></div>
        <div class="info-box"><h3>✈️ Nearest Airport</h3><ul>@forelse($nearbyGrouped->get('Airport', collect()) as $a)<li>{{ $a->name }} <span class="dist">{{ $a->distance }} {{ $a->unit }}</span></li>@empty<li class="text-muted">No data yet</li>@endforelse</ul></div>
        <div class="info-box"><h3>☕ Cafe & Bars</h3><ul>@forelse($nearbyGrouped->get('Cafe', collect()) as $a)<li>{{ $a->name }} <span class="dist">{{ $a->distance }} {{ $a->unit }}</span></li>@empty<li class="text-muted">No data yet</li>@endforelse</ul></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <section class="detail-panel">
                <h2>About this property</h2>
                <p class="text-muted mb-0">{{ $hotel->description }}</p>
            </section>

            <section class="detail-panel" id="rooms">
                <h2>Choose your room</h2>
                @forelse($hotel->roomTypes as $room)
                    <div class="room-rate">
                        <div>
                            <strong class="d-block mb-2" style="color:#17233b;">{{ $room->name }}</strong>
                            <ul>
                                <li>• {{ $room->bed_type }} · {{ $room->max_guests }} guests @if($room->size_sqm) · {{ $room->size_sqm }} m² @endif</li>
                                @if($room->breakfast_included)<li class="ok">✓ Breakfast included</li>@endif
                                @if($room->free_cancellation)<li class="ok">✓ Free cancellation</li>@endif
                                @foreach($room->amenities->take(4) as $amenity)
                                    <li>• {{ $amenity->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="room-rate__price">
                            <strong>Rp {{ number_format($room->base_price, 0, ',', '.') }}</strong>
                            <span>per night</span>
                            <button type="button" class="btn btn-primary btn-sm mt-2" disabled title="Booking flow sedang dibangun">Select Room</button>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No rooms published for this hotel yet.</p>
                @endforelse
            </section>

            <section class="detail-panel">
                <h2>Service & Amenities</h2>
                <div class="row g-2">
                    @foreach($hotel->amenities as $amenity)
                        <div class="col-6 col-md-4">✓ {{ $amenity->name }}</div>
                    @endforeach
                </div>
            </section>

            @if($hotel->policy)
                <section class="detail-panel">
                    <h2>Hotel Policies</h2>
                    <div class="row g-3">
                        <div class="col-md-6"><h4 class="small fw-bold">Check-in / Check-out</h4><p class="text-muted small">{{ $hotel->policy->check_in_policy }} {{ $hotel->policy->check_out_policy }}</p></div>
                        <div class="col-md-6"><h4 class="small fw-bold">Cancellation</h4><p class="text-muted small">{{ $hotel->policy->cancellation_policy }}</p></div>
                        <div class="col-md-6"><h4 class="small fw-bold">Children & Pets</h4><p class="text-muted small">{{ $hotel->policy->child_policy }} {{ $hotel->policy->pet_policy }}</p></div>
                        <div class="col-md-6"><h4 class="small fw-bold">Smoking & Payment</h4><p class="text-muted small">{{ $hotel->policy->smoking_policy }} {{ $hotel->policy->payment_policy }}</p></div>
                    </div>
                </section>
            @endif

            @if($nearbyGrouped->isNotEmpty())
                <section class="detail-panel">
                    <h2>Nearest Services</h2>
                    <div class="services-grid">
                        @foreach($nearbyGrouped as $category => $places)
                            <div>
                                <h4>{{ $category }}</h4>
                                <ul>@foreach($places as $place)<li>{{ $place->name }} <span class="dist">{{ $place->distance }} {{ $place->unit }}</span></li>@endforeach</ul>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="detail-panel">
                <h2>Guest Reviews</h2>
                <div class="review-score">
                    <div class="review-score__big"><strong>{{ number_format($hotel->rating_avg, 1) }}</strong><span>{{ $ratingLabel }}</span></div>
                    <div class="review-score__bars">
                        @foreach($reviewBreakdown as $label => $score)
                            <div>{{ $label }} <strong>{{ $score }}</strong><div class="bar-track"><div class="bar-fill" style="width: {{ $score * 10 }}%"></div></div></div>
                        @endforeach
                    </div>
                </div>
                <p class="text-muted small mb-3">Based on {{ $hotel->reviews_count }} verified guest reviews.</p>

                @forelse($hotel->reviews as $review)
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->user->name ?? 'Guest' }}</strong>
                            <span class="small text-muted">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        @if($review->title)<p class="fw-semibold small mb-1">{{ $review->title }}</p>@endif
                        <p class="text-muted small mb-0">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-muted small">No reviews yet — be the first to stay and share your experience.</p>
                @endforelse
            </section>

            <section class="detail-panel">
                <h2>Frequently Asked Questions</h2>
                <div class="tapgo-accordion">
                    <details class="item" open><summary>Can I pay after check-in?</summary><p>Payment method and timing depend on the room rate selected — see the payment policy above.</p></details>
                    <details class="item"><summary>Is airport transfer included?</summary><p>Check the amenities list above — hotels offering an airport shuttle will list it there.</p></details>
                    <details class="item"><summary>What is the cancellation policy?</summary><p>See the Hotel Policies section above, or look for the "Free cancellation" badge on each room rate.</p></details>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <div class="booking-card">
                <h3>Your stay</h3>
                <p class="text-muted small">{{ $hotel->name }} · {{ $hotel->city }}</p>
                <hr>
                @if($fromPrice)
                    <div class="d-flex justify-content-between mb-2"><span>From</span><strong>Rp {{ number_format($fromPrice, 0, ',', '.') }}</strong></div>
                @endif
                <p class="small text-muted">Per night, taxes and fees may apply.</p>
                <a href="#rooms" class="btn btn-primary w-100">View Room Rates</a>
            </div>
        </div>
    </div>

    @if($related->isNotEmpty())
        <section class="mt-4">
            <h2 class="h5 fw-bold mb-3">Similar Hotels & Resorts</h2>
            <div class="row g-4">
                @foreach($related as $item)
                    <div class="col-md-4">
                        <article class="travel-card h-100">
                            <a href="{{ route('hotels.show', $item) }}" class="travel-card__image">
                                <img src="{{ $item->primaryImage->image_path ?? $fallback }}" alt="{{ $item->name }}" onerror="this.onerror=null;this.src='{{ $fallback }}'">
                            </a>
                            <div class="travel-card__body">
                                <h3 class="h6 mb-1"><a href="{{ route('hotels.show', $item) }}">{{ $item->name }}</a></h3>
                                <p class="text-muted small mb-2">{{ $item->city }}, {{ $item->province }}</p>
                                @if($item->room_types_min_base_price)
                                    <p class="small fw-semibold mb-0" style="color:#17233b;">From Rp {{ number_format($item->room_types_min_base_price, 0, ',', '.') }}/night</p>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
</main>
@endsection
