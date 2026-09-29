@props(['trip'])
@php
    $fallback = 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=900&q=85';
    $image = $trip->images->first()?->image_path ? asset('storage/'.$trip->images->first()->image_path) : $fallback;
@endphp
<article class="travel-card h-100">
    <a href="{{ route('trips.show', $trip) }}" class="travel-card__image">
        <img src="{{ $image }}" alt="{{ $trip->title }}" onerror="this.onerror=null;this.src='{{ $fallback }}'">
        @if($trip->is_featured)<span class="card-label">Handpicked</span>@endif
    </a>
    <div class="travel-card__body">
        <div class="row text-center g-0 border-bottom pb-2 mb-2">
            <div class="col-3"><span class="d-block">📅</span><small class="text-muted">{{ $trip->duration }}</small></div>
            <div class="col-3"><span class="d-block">🗺️</span><small class="text-muted">{{ $trip->itineraries_count ?? $trip->itineraries->count() }} stops</small></div>
            <div class="col-3"><span class="d-block">✅</span><small class="text-muted">{{ $trip->inclusions_count ?? $trip->inclusions->count() }} incl.</small></div>
            <div class="col-3"><span class="d-block">👥</span><small class="text-muted">{{ $trip->min_group_size }}-{{ $trip->max_group_size }} pax</small></div>
        </div>
        <div class="d-flex justify-content-between gap-2 align-items-center mb-2">
            <span class="small text-muted">{{ $trip->destination->name }} · {{ $trip->category->name ?? 'Tour' }}</span>
            @if($trip->rating_avg > 0)<span class="rating">★ {{ number_format($trip->rating_avg, 1) }} <small>({{ $trip->reviews_count }})</small></span>@endif
        </div>
        <h3 class="h5 mb-2"><a href="{{ route('trips.show', $trip) }}">{{ $trip->title }}</a></h3>
        <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($trip->description, 76) }}</p>
        <div class="d-flex justify-content-between align-items-end border-top pt-3">
            <span class="small text-muted">From <strong class="d-block price">Rp {{ number_format($trip->base_price, 0, ',', '.') }}</strong><small>for {{ $trip->min_group_size }} person</small></span>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('trips.show', $trip) }}">View trip</a>
        </div>
    </div>
</article>
