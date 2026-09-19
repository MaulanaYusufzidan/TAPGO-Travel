@extends('layouts.app')
@section('title', 'Hotels — TAPGO Travel')
@section('meta_description', 'Cari dan bandingkan hotel terbaik di destinasi favorit Indonesia.')
@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="Hotels" />
    <div class="page-title-row">
        <div>
            <h1>Find your perfect stay</h1>
            <p>Handpicked places for every kind of journey.</p>
        </div>
    </div>

    <form class="compact-search row g-2" onsubmit="return false">
        <div class="col-md-4"><label>Destination</label><input class="form-control" name="destination" placeholder="Where are you going?"></div>
        <div class="col-md-2"><label>Check in</label><input type="date" class="form-control" name="check_in"></div>
        <div class="col-md-2"><label>Check out</label><input type="date" class="form-control" name="check_out"></div>
        <div class="col-md-2"><label>Guests</label><select class="form-select" name="guests"><option>1 Room, 2 guests</option><option>1 Room, 1 guest</option><option>2 Rooms, 4 guests</option></select></div>
        <div class="col-md-2 d-grid"><button class="btn btn-warning">🔍 Search</button></div>
    </form>

    <div class="row g-4">
        <div class="col-lg-3"><x-filter-sidebar/></div>
        <div class="col-lg-9">
            <div class="results-toolbar">
                <span><strong>{{ count($hotels) }} hotels found</strong> across Indonesia</span>
                <select class="form-select form-select-sm" style="max-width:200px;"><option>Recommended</option><option>Price: low to high</option><option>Price: high to low</option><option>Guest rating</option></select>
            </div>
            @foreach($hotels as $hotel)
                <article class="hotel-result">
                    <div style="position:relative;">
                        <img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] }}">
                        <span class="card-label">{{ $hotel['type'] }}</span>
                        @if($hotel['discount'] > 0)<span class="ribbon-discount">{{ $hotel['discount'] }}% Off</span>@endif
                    </div>
                    <div class="hotel-result__info">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <h2 class="mb-0"><a href="{{ route('hotels.show', $hotel['slug']) }}" class="text-decoration-none" style="color:inherit;">{{ $hotel['name'] }}</a></h2>
                            <div class="score-chip"><strong>{{ $hotel['rating'] }}</strong><small>/5</small></div>
                        </div>
                        <p class="text-muted small mb-2 mt-1">⌖ {{ $hotel['location'] }}</p>
                        <span class="small fw-semibold" style="color:#17233b;">{{ $hotel['rating_label'] }}</span>
                        <span class="small text-muted">· {{ $hotel['reviews'] }} reviews</span>
                        <div class="amenity-icons">
                            @foreach(array_slice($hotel['amenities'], 0, 4) as $amenity)
                                <span>{{ $amenity }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="hotel-result__price">
                        @if($hotel['discount'] > 0)<span class="price-was">Rp {{ $hotel['original_price'] }}</span>@endif
                        <span class="small text-muted">From</span>
                        <strong>Rp {{ $hotel['price'] }}</strong>
                        <span class="small text-muted">per night</span>
                        <a href="{{ route('hotels.show', $hotel['slug']) }}" class="btn btn-primary btn-sm mt-3">See Availability ↗</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>
</main>
@endsection
