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
            <p>Handpicked hotels across Indonesia's favorite destinations.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('hotels.index') }}">
        <div class="compact-search row g-2 align-items-end">
            <div class="col-md-4">
                <label for="destination">Destination</label>
                <input id="destination" class="form-control" name="destination" placeholder="Where are you going?" value="{{ $filters['destination'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="check_in">Check in</label>
                <input id="check_in" type="date" class="form-control" name="check_in" value="{{ $filters['check_in'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="check_out">Check out</label>
                <input id="check_out" type="date" class="form-control" name="check_out" value="{{ $filters['check_out'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="guests">Guests</label>
                <select id="guests" class="form-select" name="guests">
                    @foreach([1, 2, 3, 4] as $g)
                        <option value="{{ $g }}" @selected((string) ($filters['guests'] ?? '2') === (string) $g)>{{ $g }} {{ $g === 1 ? 'guest' : 'guests' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-warning">🔍 Search</button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3">
                <x-filter-sidebar :amenities="$amenities" :hotelTypes="$hotelTypes" :bedTypes="$bedTypes" :filters="$filters" />
            </div>
            <div class="col-lg-9">
                @if($hotels->isEmpty())
                    <div class="text-center py-5">
                        <p class="lead">No hotels match your search yet.</p>
                        <a href="{{ route('hotels.index') }}" class="btn btn-link">Clear filters</a>
                    </div>
                @else
                    <div class="results-toolbar">
                        <span><strong>{{ $hotels->total() }} hotels found</strong> across Indonesia</span>
                        @php $currentSort = $filters['sort'] ?? 'recommended'; @endphp
                        <div class="btn-group" role="group" aria-label="Sort hotels">
                            @foreach(['recommended' => 'Our Trending', 'most_reviewed' => 'Most Popular', 'price_low' => 'Lowest Price'] as $value => $label)
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $value]) }}"
                                   class="btn btn-sm {{ $currentSort === $value ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>

                    @foreach($hotels as $hotel)
                        <x-hotel-card :hotel="$hotel" />
                    @endforeach

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $hotels->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
</main>
@endsection
