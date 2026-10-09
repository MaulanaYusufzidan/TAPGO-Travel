@extends('layouts.app')

@section('title', 'TAPGO TRAVEL — Discover More. Travel Better.')

@section('content')
    @include('layouts.partials.hero')

    @if($dealHotels->isNotEmpty())
    <section class="section-space"><div class="container">
        <div class="row g-3">
            @foreach($dealHotels as $hotel)
                @php $fallback = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80'; @endphp
                <div class="col-md-4">
                    <a href="{{ route('hotels.show', $hotel) }}" class="text-decoration-none" style="color:inherit;">
                        <div class="d-flex gap-3 align-items-center border rounded-3 p-2" style="border-color:#e3e8ec !important;">
                            <div style="position:relative; width:90px; height:70px; min-width:90px; border-radius:.5rem; overflow:hidden;">
                                <img src="{{ $hotel->primaryImage->image_path ?? $fallback }}" alt="{{ $hotel->city }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src='{{ $fallback }}'">
                                <span class="ribbon-discount" style="top:4px; left:4px; right:auto;">{{ $hotel->discount_percentage }}% Off</span>
                            </div>
                            <div>
                                <strong class="d-block small" style="color:#17233b;">{{ $hotel->city }}</strong>
                                <span class="small text-muted d-block">{{ $hotel->hotel_type }}</span>
                                @if($hotel->room_types_min_base_price)
                                    <span class="small fw-semibold">From Rp {{ number_format($hotel->priceAfterDiscount($hotel->room_types_min_base_price), 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div></section>
    @endif

    @if($popularCities->isNotEmpty())
    <section class="section-space"><div class="container">
        <div class="section-heading text-center d-block"><h2>Best Attraction In Indonesia</h2><p class="text-muted">Destinasi favorit dengan hotel terbanyak di TAPGO.</p></div>
        <div class="row g-3">
            @php $fallback = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=400&q=80'; @endphp
            @foreach($popularCities as $city)
                <div class="col-6 col-md-3">
                    <a href="{{ route('hotels.index', ['destination' => $city->city]) }}" class="text-decoration-none">
                        <div style="height:140px; border-radius:.6rem; overflow:hidden;">
                            <img src="{{ $city->image ?? $fallback }}" alt="{{ $city->city }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src='{{ $fallback }}'">
                        </div>
                        <strong class="d-block small mt-2" style="color:#17233b;">{{ $city->city }}</strong>
                        <span class="small text-muted">{{ $city->hotel_count }} hotel{{ $city->hotel_count > 1 ? 's' : '' }}</span>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4"><a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">Explore More ↗</a></div>
    </div></section>
    @endif

    <section class="section-space" id="destinations"><div class="container"><div class="section-heading"><div><p class="eyebrow">Explore Indonesia</p><h2>Places that stay with you</h2></div><a href="{{ route('destinations.index') }}" class="text-link">View all destinations <span>→</span></a></div><div class="row g-4">@forelse($featuredDestinations as $destination)<div class="col-sm-6 col-lg-4"><x-destination-card :destination="$destination" /></div>@empty <div class="col"><p class="text-muted">New destinations are being curated. Check back soon.</p></div>@endforelse</div></div></section>
    <section class="section-space section-tint" id="tours"><div class="container"><div class="section-heading"><div><p class="eyebrow">Travel your way</p><h2>Curated experiences</h2><p class="text-muted mb-0">Thoughtful itineraries, local knowledge, and the freedom to explore.</p></div><a href="{{ route('trips.index') }}" class="text-link">Browse all trips <span>→</span></a></div><div class="row g-4">@forelse($featuredTrips as $trip)<div class="col-sm-6 col-lg-4"><x-trip-card :trip="$trip" /></div>@empty <div class="col"><p class="text-muted">Our next collection of journeys is on its way.</p></div>@endforelse</div></div></section>

    @if($popularCities->isNotEmpty())
    <section class="section-space section-tint"><div class="container">
        <div class="section-heading text-center d-block"><h2>Popular Location To Stay</h2><p class="text-muted">Kota dengan pilihan hotel terbanyak di platform kami.</p></div>
        <div class="row g-3">
            @php $fallback = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=400&q=80'; @endphp
            @foreach($popularCities as $city)
                <div class="col-6 col-md-3">
                    <a href="{{ route('hotels.index', ['destination' => $city->city]) }}" style="position:relative; display:block; height:140px; border-radius:.6rem; overflow:hidden; color:#fff; text-decoration:none;">
                        <img src="{{ $city->image ?? $fallback }}" alt="{{ $city->city }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;" onerror="this.onerror=null;this.src='{{ $fallback }}'">
                        <span style="position:absolute; inset:0; background:linear-gradient(0deg, rgba(10,20,35,.75), rgba(10,20,35,.1));"></span>
                        <span style="position:absolute; left:12px; bottom:10px;"><strong class="d-block">{{ $city->city }}</strong><small>{{ $city->hotel_count }} hotels</small></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div></section>
    @endif

    @if($trendingHotels->isNotEmpty())
    <section class="section-space"><div class="container">
        <div class="section-heading text-center d-block"><h2>Hot & Trending Venues</h2><p class="text-muted">Hotel dengan rating tertinggi di TAPGO.</p></div>
        <div class="row g-4">
            @php $fallback = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80'; @endphp
            @foreach($trendingHotels as $hotel)
                <div class="col-md-3">
                    <article class="travel-card h-100">
                        <a href="{{ route('hotels.show', $hotel) }}" class="travel-card__image">
                            <img src="{{ $hotel->primaryImage->image_path ?? $fallback }}" alt="{{ $hotel->name }}" onerror="this.onerror=null;this.src='{{ $fallback }}'">
                        </a>
                        <div class="travel-card__body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h3 class="h6 mb-1"><a href="{{ route('hotels.show', $hotel) }}" style="color:inherit; text-decoration:none;">{{ $hotel->city }}</a></h3>
                                @if($hotel->rating_avg > 0)<span class="rating">★ {{ number_format($hotel->rating_avg, 1) }}</span>@endif
                            </div>
                            <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($hotel->short_description, 70) }}</p>
                            <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                @if($hotel->room_types_min_base_price)
                                    <span class="small text-muted">From <strong class="d-block price">Rp {{ number_format($hotel->room_types_min_base_price, 0, ',', '.') }}</strong></span>
                                @endif
                                <span class="small text-muted">{{ $hotel->room_types_sum_quantity }} rooms</span>
                            </div>
                            <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">View Hotel ↗</a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div></section>
    @endif

    <section class="section-space section-tint"><div class="container"><div class="section-heading"><div><p class="eyebrow">Fly for less</p><h2>Flight deals for your next escape</h2></div><a href="{{ route('flights.index') }}" class="text-link">Find flights →</a></div><div class="row g-3">@php $fallback = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=400&q=80'; @endphp @forelse(\App\Models\FlightOffer::published()->with('departureFlight.airline')->orderBy('base_price')->take(3)->get() as $offer)<div class="col-lg-4"><div class="flight-deal"><span>{{ $offer->departureFlight->airline->name }} · {{ $offer->departureFlight->flight_number }}</span><div><strong>{{ $offer->departureFlight->origin_code }}</strong><i>→</i><strong>{{ $offer->departureFlight->destination_code }}</strong></div><small>{{ $offer->departureFlight->departure_at->format('H:i') }} · {{ $offer->departureFlight->stops_label }} · {{ $offer->departureFlight->duration_label }}</small><footer><b>Rp {{ number_format($offer->final_price, 0, ',', '.') }}</b><a href="{{ route('flights.show', $offer) }}">Select</a></footer></div></div>@empty<div class="col"><p class="text-muted">Flight deals are being curated. Check back soon.</p></div>@endforelse</div></div></section>

    <section class="section-space"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-5"><p class="eyebrow">Why TAPGO</p><h2 class="mb-3">Travel plans, made refreshingly simple.</h2><p class="text-muted mb-0">From a spark of curiosity to a confirmed itinerary, TAPGO keeps every decision clear and every journey personal.</p></div><div class="col-lg-7"><div class="row g-3"><div class="col-sm-6"><div class="feature-card"><span>01</span><h3>Easy booking</h3><p>Compare trips and reserve your place in a few confident steps.</p></div></div><div class="col-sm-6"><div class="feature-card"><span>02</span><h3>Trusted local teams</h3><p>Every trip is designed with partners who know the destination deeply.</p></div></div><div class="col-sm-6"><div class="feature-card"><span>03</span><h3>Clear pricing</h3><p>See what is included before you commit, with no unnecessary surprises.</p></div></div><div class="col-sm-6"><div class="feature-card"><span>04</span><h3>Here when you need us</h3><p>Helpful support before departure and throughout your journey.</p></div></div></div></div></div></div></section>

    @if($reviews->isNotEmpty())
    <section class="section-space section-tint"><div class="container">
        <div class="section-heading text-center d-block"><h2>Loving Reviews By Our Customers</h2><p class="text-muted">Cerita asli dari tamu yang sudah menginap.</p></div>
        <div class="row g-3">
            @foreach($reviews as $review)
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100 bg-white" style="border-color:#e3e8ec !important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:44px;height:44px;border-radius:50%;background:#eef3fb;display:flex;align-items:center;justify-content:center;font-weight:700;color:#1968e0;">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                            <div><strong class="d-block small">{{ $review->user->name ?? 'Guest' }}</strong><span class="small text-muted">{{ $review->hotel->city ?? '' }}</span></div>
                        </div>
                        <span class="small text-warning">{{ str_repeat('★', min(5, intdiv($review->rating, 2) ?: 1)) }}</span>
                        <p class="small text-muted mt-1 mb-0">{{ \Illuminate\Support\Str::limit($review->comment, 140) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div></section>
    @endif

    <section class="container pb-5"><div class="campaign-banner"><div><p class="eyebrow text-warning mb-2">Make time for wonder</p><h2>Where will your next story begin?</h2><p>Find the island, city, or mountain trail that calls to you.</p></div><a href="{{ route('destinations.index') }}" class="btn btn-warning btn-lg">Explore destinations</a></div></section>
    <section class="section-space pt-4" id="inspiration"><div class="container"><div class="section-heading"><div><p class="eyebrow">Travel notes</p><h2>Inspiration for the curious</h2></div></div><div class="row g-4"><div class="col-md-4"><article class="journal-card"><img src="https://images.unsplash.com/photo-1539367628448-4bc5c9d171c8?auto=format&fit=crop&w=900&q=85" alt="Balinese temple beside water"><div><p class="eyebrow">Destination guide</p><h3>Slow mornings and sacred spaces in Bali</h3><a href="{{ route('destinations.index', ['q' => 'Bali']) }}" class="text-link">Read guide →</a></div></article></div><div class="col-md-4"><article class="journal-card"><img src="https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=85" alt="Ancient temple in Yogyakarta"><div><p class="eyebrow">Itinerary</p><h3>A considered long weekend in Yogyakarta</h3><a href="{{ route('destinations.index', ['q' => 'Yogyakarta']) }}" class="text-link">Read guide →</a></div></article></div><div class="col-md-4"><article class="journal-card"><img src="https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=900&q=85" alt="Tropical beach landscape"><div><p class="eyebrow">Travel tips</p><h3>How to make more of every island escape</h3><a href="{{ route('trips.index') }}" class="text-link">Read guide →</a></div></article></div></div></div></section>
@endsection
