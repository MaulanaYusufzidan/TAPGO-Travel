@props(['offer'])
@php
    $dep = $offer->departureFlight;
    $ret = $offer->returnFlight;
    $finalPrice = $offer->final_price;
    $cityImages = [
        'Bali' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=300&q=80',
        'Surabaya' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=300&q=80',
        'Yogyakarta' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=300&q=80',
        'Medan' => 'https://images.unsplash.com/photo-1591474200742-8e512e6f98f8?auto=format&fit=crop&w=300&q=80',
        'Makassar' => 'https://images.unsplash.com/photo-1580746738099-1a9358a56b8f?auto=format&fit=crop&w=300&q=80',
        'Lombok' => 'https://images.unsplash.com/photo-1559628233-100c798642d4?auto=format&fit=crop&w=300&q=80',
        'Singapore' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=300&q=80',
        'Kuala Lumpur' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=300&q=80',
    ];
    $thumbFallback = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=300&q=80';
    $thumb = $cityImages[$dep->destination_city] ?? $thumbFallback;
@endphp
<article class="border rounded-3 p-3 mb-3 bg-white" style="border-color:#e3e8ec !important;">
    <div class="row align-items-center g-3">
        <div class="col-md-2 d-none d-md-block">
            <div style="height:80px; border-radius:.5rem; overflow:hidden;">
                <img src="{{ $thumb }}" alt="{{ $dep->destination_city }}" style="width:100%; height:100%; object-fit:cover; display:block;" onerror="this.onerror=null;this.src='{{ $thumbFallback }}'">
            </div>
        </div>
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge text-bg-light border">Departure</span>
                <span class="small text-muted">{{ $dep->departure_at->translatedFormat('d M Y') }}</span>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="airline-mark">✈</div>
                <div class="flight-airline"><strong>{{ $dep->airline->name }}</strong><span>{{ $dep->flight_number }} · {{ $dep->travel_class }}</span></div>
                <div class="flight-time"><strong>{{ $dep->departure_at->format('H:i') }}</strong><span>{{ $dep->origin_code }}</span></div>
                <div class="flight-route"><span>{{ $dep->duration_label }}</span><i></i><small>{{ $dep->stops_label }}</small></div>
                <div class="flight-time"><strong>{{ $dep->arrival_at->format('H:i') }}</strong><span>{{ $dep->destination_code }}</span></div>
            </div>

            @if($ret)
                <hr class="my-2">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge text-bg-light border">Return</span>
                    <span class="small text-muted">{{ $ret->departure_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="airline-mark">✈</div>
                    <div class="flight-airline"><strong>{{ $ret->airline->name }}</strong><span>{{ $ret->flight_number }} · {{ $ret->travel_class }}</span></div>
                    <div class="flight-time"><strong>{{ $ret->departure_at->format('H:i') }}</strong><span>{{ $ret->origin_code }}</span></div>
                    <div class="flight-route"><span>{{ $ret->duration_label }}</span><i></i><small>{{ $ret->stops_label }}</small></div>
                    <div class="flight-time"><strong>{{ $ret->arrival_at->format('H:i') }}</strong><span>{{ $ret->destination_code }}</span></div>
                </div>
            @endif
        </div>

        <div class="col-md-3 text-md-end border-start-md ps-md-3">
            @if($offer->discount_percentage)
                <span class="ribbon-discount" style="position:static; display:inline-block; margin-bottom:.3rem;">{{ $offer->discount_percentage }}% Off</span><br>
                <span class="price-was">Rp {{ number_format($offer->base_price, 0, ',', '.') }}</span><br>
            @endif
            <strong class="d-block" style="font-size:1.15rem; color:#17233b;">Rp {{ number_format($finalPrice, 0, ',', '.') }}</strong>
            <span class="small text-muted">{{ $offer->refundable ? 'Refundable' : 'Non-Refundable' }}</span>
            <a href="{{ route('flights.show', $offer) }}" class="btn btn-primary btn-sm mt-2 d-block">Select Flight ↗</a>
        </div>
    </div>
</article>
