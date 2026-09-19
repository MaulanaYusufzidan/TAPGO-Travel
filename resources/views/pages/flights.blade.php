@extends('layouts.app')
@section('title', 'Flights — TAPGO Travel')
@section('meta_description', 'Bandingkan penerbangan terbaik ke berbagai destinasi di Indonesia.')
@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="Flights" />
    <div class="page-title-row">
        <div>
            <h1>Compare flights with confidence</h1>
            <p>Choose the route and fare that fits your journey.</p>
        </div>
    </div>

    <form class="compact-search row g-2" onsubmit="return false">
        <div class="col-md-3"><label>From</label><input class="form-control" name="from" placeholder="Departure city"></div>
        <div class="col-md-3"><label>To</label><input class="form-control" name="to" placeholder="Arrival city"></div>
        <div class="col-md-2"><label>Departure</label><input type="date" class="form-control" name="departure"></div>
        <div class="col-md-2"><label>Travelers</label><select class="form-select" name="travelers"><option>1 Adult</option><option>2 Adults</option><option>Family</option></select></div>
        <div class="col-md-2 d-grid"><button class="btn btn-warning">🔍 Search</button></div>
    </form>

    <div class="row g-4">
        <div class="col-lg-3">
            <aside class="filter-sidebar">
                <strong class="d-block mb-3">Filter flights</strong>
                <div class="filter-group"><h3>Stops</h3><label><input type="checkbox"> Non-stop</label><label><input type="checkbox"> 1 stop</label></div>
                <div class="filter-group"><h3>Airlines</h3>
                    @foreach(collect($flights)->pluck('airline')->unique() as $airline)
                        <label><input type="checkbox"> {{ $airline }}</label>
                    @endforeach
                </div>
                <div class="filter-group"><h3>Departure time</h3><label><input type="checkbox"> Morning</label><label><input type="checkbox"> Afternoon</label></div>
            </aside>
        </div>
        <div class="col-lg-9">
            <div class="results-toolbar">
                <span><strong>{{ count($flights) }} flights found</strong></span>
                <select class="form-select form-select-sm" style="max-width:200px;"><option>Recommended</option><option>Lowest price</option></select>
            </div>
            @foreach($flights as $flight)
                <article class="flight-result">
                    <div class="airline-mark">✈</div>
                    <div class="flight-airline"><strong>{{ $flight['airline'] }}</strong><span>{{ $flight['number'] }}</span></div>
                    <div class="flight-time"><strong>{{ $flight['depart'] }}</strong><span>{{ $flight['from'] }}</span></div>
                    <div class="flight-route"><span>{{ $flight['duration'] }}</span><i></i><small>{{ $flight['stops'] }}</small></div>
                    <div class="flight-time"><strong>{{ $flight['arrive'] }}</strong><span>{{ $flight['to'] }}</span></div>
                    <div class="hotel-result__price">
                        @if(($flight['original_price'] ?? $flight['price']) !== $flight['price'])
                            <span class="price-was">Rp {{ $flight['original_price'] }}</span>
                        @endif
                        <strong>Rp {{ $flight['price'] }}</strong>
                        <span class="small text-muted">per traveler</span>
                        <a href="{{ route('flights.show', $flight['id']) }}" class="btn btn-primary btn-sm mt-2">Select Flight ↗</a>
                    </div>
                </article>
                <div class="amenity-icons mb-3" style="margin-top:-.6rem;">
                    @foreach($flight['amenities'] as $amenity)<span>{{ $amenity }}</span>@endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
</main>
@endsection
