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
                @if($hotel->discount_percentage)
                    <span class="ribbon-discount" style="position:static; display:inline-block; margin-bottom:.3rem;">{{ $hotel->discount_percentage }}% Off</span>
                    <p class="price-was mb-0">Rp {{ number_format($originalFromPrice, 0, ',', '.') }}</p>
                @endif
                <p class="h4 fw-bold mb-1" style="color:#17233b;">Rp {{ number_format($fromPrice, 0, ',', '.') }}</p>
                <span class="small text-muted d-block mb-2">per night</span>
            @endif
            <a href="#rooms" class="btn btn-primary">Select Rooms</a>
        </div>
    </div>

    @php $allImages = $hotel->images->values(); @endphp
    <div class="gallery-grid" style="height: 380px;">
        <div class="gallery-grid__main">
            <a href="#" onclick="tapgoLightboxOpen(event, 0)"><img src="{{ $mainImage->image_path ?? $fallback }}" alt="{{ $hotel->name }}" onerror="this.onerror=null;this.src='{{ $fallback }}'"></a>
        </div>
        <div class="gallery-grid__thumbs">
            @foreach($thumbs as $i => $img)
                @php $imgIndex = $allImages->search(fn ($x) => $x->id === $img->id); @endphp
                <a href="#" onclick="tapgoLightboxOpen(event, {{ $imgIndex }})" style="position:relative; display:block;">
                    <img src="{{ $img->image_path }}" alt="{{ $hotel->name }} photo {{ $i + 1 }}">
                    @if($loop->last && $allImages->count() > 5)
                        <span style="position:absolute; inset:0; background:rgba(0,0,0,.5); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; border-radius:inherit;">+{{ $allImages->count() - 5 }} More Photos</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <div id="tapgo-lightbox" style="display:none; position:fixed; inset:0; background:rgba(10,15,25,.92); z-index:1050; align-items:center; justify-content:center;">
        <button type="button" onclick="tapgoLightboxClose()" aria-label="Close" style="position:absolute; top:20px; right:24px; background:none; border:none; color:#fff; font-size:2rem; line-height:1; cursor:pointer;">&times;</button>
        <button type="button" onclick="tapgoLightboxNav(-1)" aria-label="Previous" style="position:absolute; left:16px; background:none; border:none; color:#fff; font-size:2.5rem; cursor:pointer;">&#8249;</button>
        <img id="tapgo-lightbox-img" src="" alt="{{ $hotel->name }}" style="max-width:88vw; max-height:82vh; object-fit:contain; border-radius:.5rem;">
        <button type="button" onclick="tapgoLightboxNav(1)" aria-label="Next" style="position:absolute; right:16px; background:none; border:none; color:#fff; font-size:2.5rem; cursor:pointer;">&#8250;</button>
        <span id="tapgo-lightbox-counter" style="position:absolute; bottom:20px; color:#fff; font-size:.85rem;"></span>
    </div>
    <script>
        window.tapgoGalleryImages = @json($allImages->pluck('image_path')->values());
        window.tapgoLightboxIndex = 0;
        function tapgoLightboxRender() {
            var imgs = window.tapgoGalleryImages;
            if (!imgs.length) return;
            document.getElementById('tapgo-lightbox-img').src = imgs[window.tapgoLightboxIndex];
            document.getElementById('tapgo-lightbox-counter').textContent = (window.tapgoLightboxIndex + 1) + ' / ' + imgs.length;
        }
        function tapgoLightboxOpen(e, index) {
            e.preventDefault();
            window.tapgoLightboxIndex = index || 0;
            tapgoLightboxRender();
            document.getElementById('tapgo-lightbox').style.display = 'flex';
        }
        function tapgoLightboxClose() {
            document.getElementById('tapgo-lightbox').style.display = 'none';
        }
        function tapgoLightboxNav(dir) {
            var imgs = window.tapgoGalleryImages;
            window.tapgoLightboxIndex = (window.tapgoLightboxIndex + dir + imgs.length) % imgs.length;
            tapgoLightboxRender();
        }
    </script>

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

                @if($hotel->roomTypes->isEmpty())
                    <p class="text-muted">No rooms published for this hotel yet.</p>
                @else
                    <form method="POST" action="{{ route('hotel-bookings.store') }}">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="small text-muted">Check in</label>
                                <input type="date" name="check_in" class="form-control form-control-sm" value="{{ old('check_in', $stay['check_in']) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted">Check out</label>
                                <input type="date" name="check_out" class="form-control form-control-sm" value="{{ old('check_out', $stay['check_out']) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted">Guests</label>
                                <input type="number" name="guests" min="1" class="form-control form-control-sm" value="{{ old('guests', $stay['guests']) }}" required>
                            </div>
                        </div>
                        @error('quantity') <p class="text-danger small">{{ $message }}</p> @enderror
                        @error('guests') <p class="text-danger small">{{ $message }}</p> @enderror

                        @guest
                            <p class="small text-muted">Please <a href="{{ route('login') }}">login</a> to select a room and book.</p>
                        @endguest

                        @foreach($hotel->roomTypes as $room)
                            @php $roomImage = $room->images->first()->image_path ?? null; @endphp
                            <div class="room-rate" style="align-items:flex-start;">
                                @if($roomImage)
                                    <img src="{{ $roomImage }}" alt="{{ $room->name }}" style="width:96px; height:96px; min-width:96px; object-fit:cover; border-radius:.5rem;" onerror="this.style.display='none'">
                                @endif
                                <div style="flex:1;">
                                    <strong class="d-block mb-2" style="color:#17233b;">{{ $room->name }}</strong>
                                    <ul>
                                        <li>• {{ $room->bed_type }} · {{ $room->max_guests }} guests @if($room->size_sqm) · {{ $room->size_sqm }} m² @endif</li>
                                        @foreach($room->amenities->take(4) as $amenity)
                                            <li>• {{ $amenity->name }}</li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-2">
                                        <label class="small text-muted d-block mb-1">Rooms</label>
                                        <input type="number" name="quantity[{{ $room->id }}]" value="1" min="1" max="{{ $room->quantity }}" class="form-control form-control-sm" style="width:80px;" aria-label="Number of rooms">
                                    </div>
                                </div>
                                <div style="min-width:220px;">
                                    @forelse($room->ratePlans as $plan)
                                        <div class="border rounded p-2 mb-2" style="border-color:#e9edf0 !important;">
                                            <p class="small fw-semibold mb-1" style="color:#17233b;">{{ $plan->name }}</p>
                                            <ul class="mb-2" style="list-style:none; padding:0; font-size:.76rem; color:#667384;">
                                                @if($plan->breakfast_included)<li class="ok">✓ Breakfast included</li>@endif
                                                @if($plan->free_cancellation)<li class="ok">✓ Free cancellation</li>@endif
                                                <li>{{ $plan->refundable ? 'Refundable' : 'Non-refundable' }}</li>
                                            </ul>
                                            <strong class="d-block">Rp {{ number_format($room->base_price + $plan->price_addon, 0, ',', '.') }}</strong>
                                            <span class="small text-muted">per night</span>
                                            @auth
                                                <button type="submit" name="selection" value="{{ $room->id }}:{{ $plan->id }}" class="btn btn-primary btn-sm mt-2 w-100">Select Room</button>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm mt-2 w-100" disabled>Login to book</button>
                                            @endauth
                                        </div>
                                    @empty
                                        <strong class="d-block">Rp {{ number_format($room->base_price, 0, ',', '.') }}</strong>
                                        <span class="small text-muted">per night</span>
                                        @auth
                                            <button type="submit" name="selection" value="{{ $room->id }}:" class="btn btn-primary btn-sm mt-2 w-100">Select Room</button>
                                        @else
                                            <button type="button" class="btn btn-primary btn-sm mt-2 w-100" disabled>Login to book</button>
                                        @endauth
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </form>
                @endif
            </section>

            <section class="detail-panel">
                <h2>Service & Amenities</h2>
                <div class="row g-2 mb-3">
                    @foreach($hotel->amenities as $amenity)
                        <div class="col-6 col-md-4">✓ {{ $amenity->name }}</div>
                    @endforeach
                </div>
                @php $amenityPhotos = $hotel->amenities->whereNotNull('image_path')->take(4); @endphp
                @if($amenityPhotos->isNotEmpty())
                    <div class="row g-2">
                        @foreach($amenityPhotos as $amenity)
                            <div class="col-6 col-md-3">
                                <img src="{{ $amenity->image_path }}" alt="{{ $amenity->name }}" style="width:100%; height:90px; object-fit:cover; border-radius:.5rem;" onerror="this.closest('.col-6').style.display='none'">
                                <p class="small text-muted mt-1 mb-0 text-center">{{ $amenity->name }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
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
                    @if($hotel->discount_percentage)
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted small">Was</span><span class="price-was">Rp {{ number_format($originalFromPrice, 0, ',', '.') }}</span></div>
                    @endif
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
                                    @php $itemPrice = $item->priceAfterDiscount($item->room_types_min_base_price); @endphp
                                    @if($item->discount_percentage)
                                        <span class="price-was d-block">Rp {{ number_format($item->room_types_min_base_price, 0, ',', '.') }}</span>
                                    @endif
                                    <p class="small fw-semibold mb-0" style="color:#17233b;">From Rp {{ number_format($itemPrice, 0, ',', '.') }}/night</p>
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
