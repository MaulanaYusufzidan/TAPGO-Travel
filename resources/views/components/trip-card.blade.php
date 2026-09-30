@props(['trip'])
@php
    $fallback = 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=900&q=85';
    $image = $trip->images->first()?->image_path ? asset('storage/'.$trip->images->first()->image_path) : $fallback;
    $finalPrice = $trip->priceAfterDiscount();
@endphp
<article class="travel-card h-100" style="position:relative;">
    <a href="{{ route('trips.show', $trip) }}" class="travel-card__image">
        <img src="{{ $image }}" alt="{{ $trip->title }}" onerror="this.onerror=null;this.src='{{ $fallback }}'">
        @if($trip->discount_percentage)<span class="ribbon-discount">{{ $trip->discount_percentage }}% Off</span>@endif
    </a>
    <div class="travel-card__body">
        <div class="row text-center g-0 border-bottom pb-2 mb-2">
            <div class="col-3"><span class="d-block">📅</span><small class="text-muted">{{ $trip->duration }}</small></div>
            <div class="col-3"><span class="d-block">🗺️</span><small class="text-muted">{{ $trip->itineraries_count ?? $trip->itineraries->count() }} stops</small></div>
            <div class="col-3"><span class="d-block">✅</span><small class="text-muted">{{ $trip->inclusions_count ?? $trip->inclusions->count() }} incl.</small></div>
            <div class="col-3"><span class="d-block">👥</span><small class="text-muted">{{ $trip->min_group_size }}-{{ $trip->max_group_size }} pax</small></div>
        </div>

        <h3 class="h6 mb-1"><a href="{{ route('trips.show', $trip) }}" style="color:#17233b; text-decoration:none;">{{ $trip->title }}</a></h3>
        @if($trip->rating_avg > 0)
            <span class="small text-warning">★ {{ number_format($trip->rating_avg, 1) }}</span>
            <span class="small text-muted">({{ $trip->reviews_count }} Reviews)</span>
        @endif

        <p class="small text-muted mt-2 mb-2">{{ $trip->destination->name }} · {{ $trip->duration }}</p>

        <div class="d-flex justify-content-between align-items-end border-top pt-2 mt-2">
            <span>
                @if($trip->discount_percentage)
                    <span class="price-was d-block">Rp {{ number_format($trip->base_price, 0, ',', '.') }}</span>
                @endif
                <strong class="d-block" style="color:#1968e0; font-size:1.1rem;">Rp {{ number_format($finalPrice, 0, ',', '.') }}</strong>
                <small class="text-muted">For {{ $trip->min_group_size }} Person</small>
            </span>
        </div>
    </div>
</article>
