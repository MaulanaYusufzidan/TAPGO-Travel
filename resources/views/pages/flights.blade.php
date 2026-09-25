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

    <form method="GET" action="{{ route('flights.index') }}">
        <div class="compact-search row g-2 align-items-end">
            <div class="col-md-3">
                <label for="from">Leaving From</label>
                <input id="from" class="form-control" name="from" placeholder="Departure city" value="{{ $filters['from'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="to">Going To</label>
                <input id="to" class="form-control" name="to" placeholder="Arrival city" value="{{ $filters['to'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="departure_date">Journey Date</label>
                <input id="departure_date" type="date" class="form-control" name="departure_date" value="{{ $filters['departure_date'] ?? '' }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-warning">🔍 Search</button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3">
                <aside class="filter-sidebar">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Filter by</strong>
                        <a href="{{ route('flights.index') }}" class="btn btn-link btn-sm p-0">Clear all</a>
                    </div>

                    @php
                        $timeBuckets = ['before_6am' => 'Before 6AM', '6am_12pm' => '6AM - 12PM', '12pm_6pm' => '12PM - 6PM', 'after_6pm' => 'After 6PM'];
                        $stopOptions = ['direct' => 'Direct', '1_stop' => '1 Stop', '2_plus' => '2+ Stop'];
                        $selectedOnward = $filters['onward_stops'] ?? [];
                        $selectedReturn = $filters['return_stops'] ?? [];
                        $selectedAirlines = array_map('intval', $filters['airlines'] ?? []);
                    @endphp

                    <div class="filter-group">
                        <h3>Departure time</h3>
                        @foreach($timeBuckets as $value => $label)
                            <label><input type="radio" name="departure_time" value="{{ $value }}" @checked(($filters['departure_time'] ?? '') === $value)> {{ $label }}</label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <h3>Return time</h3>
                        @foreach($timeBuckets as $value => $label)
                            <label><input type="radio" name="return_time" value="{{ $value }}" @checked(($filters['return_time'] ?? '') === $value)> {{ $label }}</label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <h3>Onward Stops</h3>
                        @foreach($stopOptions as $value => $label)
                            <label><input type="checkbox" name="onward_stops[]" value="{{ $value }}" @checked(in_array($value, $selectedOnward, true))> {{ $label }}</label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <h3>Return Stops</h3>
                        @foreach($stopOptions as $value => $label)
                            <label><input type="checkbox" name="return_stops[]" value="{{ $value }}" @checked(in_array($value, $selectedReturn, true))> {{ $label }}</label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <h3>Price range</h3>
                        <div class="d-flex gap-2">
                            <input type="number" min="0" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ $filters['min_price'] ?? '' }}">
                            <input type="number" min="0" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ $filters['max_price'] ?? '' }}">
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3>Facilities</h3>
                        <label><input type="checkbox" name="wifi" value="1" @checked(!empty($filters['wifi']))> WiFi</label>
                        <label><input type="checkbox" name="meal" value="1" @checked(!empty($filters['meal']))> In-flight Meal</label>
                    </div>

                    <div class="filter-group">
                        <h3>Preferred Airlines</h3>
                        @foreach($airlines as $airline)
                            <label><input type="checkbox" name="airlines[]" value="{{ $airline->id }}" @checked(in_array($airline->id, $selectedAirlines, true))> {{ $airline->name }}</label>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Apply filters</button>
                </aside>
            </div>
            <div class="col-lg-9">
                @if($offers->isEmpty())
                    <div class="text-center py-5">
                        <p class="lead">No flights match your search yet.</p>
                        <a href="{{ route('flights.index') }}" class="btn btn-link">Clear filters</a>
                    </div>
                @else
                    <div class="results-toolbar">
                        <span><strong>{{ $offers->total() }} flights found</strong></span>
                        @php $currentSort = $filters['sort'] ?? 'recommended'; @endphp
                        <div class="btn-group" role="group" aria-label="Sort flights">
                            @foreach(['recommended' => 'Our Trending', 'most_reviewed' => 'Most Popular', 'price_low' => 'Lowest Price'] as $value => $label)
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $value]) }}"
                                   class="btn btn-sm {{ $currentSort === $value ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>

                    @foreach($offers as $offer)
                        <x-flight-offer-card :offer="$offer" />
                    @endforeach

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $offers->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
</main>
@endsection
