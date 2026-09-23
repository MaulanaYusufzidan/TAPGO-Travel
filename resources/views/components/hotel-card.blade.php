@props(['hotel'])
@php
    $fallback = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85';
    $image = $hotel->primaryImage->image_path ?? $hotel->images->first()->image_path ?? $fallback;
    $fromPrice = $hotel->room_types_min_base_price ?? null;

    $ratingLabel = match (true) {
        $hotel->rating_avg >= 9 => 'Exceptional',
        $hotel->rating_avg >= 8 => 'Excellent',
        $hotel->rating_avg >= 7 => 'Very Good',
        $hotel->rating_avg > 0 => 'Good',
        default => null,
    };
@endphp
<article class="hotel-result">
    <div style="position:relative; height:200px; overflow:hidden;">
        <a href="{{ route('hotels.show', $hotel) }}" style="display:block; width:100%; height:100%;">
            <img src="{{ $image }}" alt="{{ $hotel->name }}" style="width:100%; height:100%; object-fit:cover; display:block;" onerror="this.onerror=null;this.src='{{ $fallback }}'">
        </a>
        @if($hotel->hotel_type)<span class="card-label">{{ $hotel->hotel_type }}</span>@endif
        @if($hotel->is_featured)<span class="ribbon-discount">Featured</span>@endif
    </div>
    <div class="hotel-result__info">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <h2 class="mb-0 h5"><a href="{{ route('hotels.show', $hotel) }}" class="text-decoration-none" style="color:inherit;">{{ $hotel->name }}</a></h2>
            @if($hotel->rating_avg > 0)
                <div class="score-chip"><strong>{{ number_format($hotel->rating_avg, 1) }}</strong><small>/10</small></div>
            @endif
        </div>
        <p class="text-muted small mb-2 mt-1">⌖ {{ $hotel->city }}, {{ $hotel->province }}</p>
        @if($ratingLabel)
            <span class="small fw-semibold" style="color:#17233b;">{{ $ratingLabel }}</span>
            <span class="small text-muted">· {{ $hotel->reviews_count }} reviews</span>
        @endif
        <div class="amenity-icons">
            @foreach($hotel->amenities->take(4) as $amenity)
                <span>{{ $amenity->name }}</span>
            @endforeach
        </div>
    </div>
    <div class="hotel-result__price">
        <span class="small text-muted">From</span>
        @if($fromPrice)
            <strong>Rp {{ number_format($fromPrice, 0, ',', '.') }}</strong>
            <span class="small text-muted">per night</span>
        @else
            <strong class="text-muted small">Room rates unavailable</strong>
        @endif
        <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-primary btn-sm mt-3">See Availability ↗</a>
    </div>
</article>
