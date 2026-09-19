@extends('layouts.app')

@section('title', 'Trips — TAPGO TRAVEL')
@section('meta_description', 'Jelajahi paket perjalanan terbaik ke seluruh Indonesia bersama TAPGO TRAVEL.')

@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="Trips" />
    <div class="page-title-row">
        <div>
            <h1>Explore trips across Indonesia</h1>
            <p>Compare locally curated journeys and book when the timing feels right.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('trips.index') }}" class="compact-search row g-2 align-items-end">
        <div class="col-md-4">
            <label for="q">Search</label>
            <input type="text" name="q" id="q" class="form-control" placeholder="Trip name..." value="{{ $filters['q'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <label for="destination">Destination</label>
            <select name="destination" id="destination" class="form-select">
                <option value="">All destinations</option>
                @foreach ($destinations as $d)
                    <option value="{{ $d->slug }}" @selected(($filters['destination'] ?? '') === $d->slug)>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="sort">Sort by</label>
            <select name="sort" id="sort" class="form-select">
                <option value="recommended" @selected(($filters['sort'] ?? 'recommended') === 'recommended')>Recommended</option>
                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price: low to high</option>
                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price: high to low</option>
                <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Guest rating</option>
                <option value="popularity" @selected(($filters['sort'] ?? '') === 'popularity')>Popularity</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-warning">🔍 Search</button>
        </div>
    </form>

    <div class="row g-4">
        <div class="col-lg-3">
            <aside class="filter-sidebar">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Filter by</strong>
                    <a href="{{ route('trips.index') }}" class="btn btn-link btn-sm p-0">Clear all</a>
                </div>
                <div class="filter-group">
                    <h3>Category</h3>
                    @foreach ($categories as $c)
                        <label>
                            <input type="radio" name="category" form="trip-filter-form" value="{{ $c->slug }}" @checked(($filters['category'] ?? '') === $c->slug) onchange="this.form.requestSubmit()">
                            {{ $c->name }}
                        </label>
                    @endforeach
                </div>
                <div class="filter-group">
                    <h3>Price range (Rp)</h3>
                    <form id="trip-filter-form" method="GET" action="{{ route('trips.index') }}" class="d-flex gap-2">
                        <input type="hidden" name="q" value="{{ $filters['q'] ?? '' }}">
                        <input type="hidden" name="destination" value="{{ $filters['destination'] ?? '' }}">
                        <input type="hidden" name="sort" value="{{ $filters['sort'] ?? '' }}">
                        <input class="form-control form-control-sm" type="number" name="price_min" placeholder="Min" value="{{ $filters['price_min'] ?? '' }}">
                        <input class="form-control form-control-sm" type="number" name="price_max" placeholder="Max" value="{{ $filters['price_max'] ?? '' }}">
                    </form>
                    <button type="submit" form="trip-filter-form" class="btn btn-outline-primary btn-sm mt-2 w-100">Apply</button>
                </div>
                <div class="filter-group">
                    <h3>Guest rating</h3>
                    @foreach (['4.5+','4+','3.5+','3+'] as $r)
                        <label><input type="checkbox" disabled> {{ $r }} stars</label>
                    @endforeach
                </div>
            </aside>
        </div>

        <div class="col-lg-9">
            <div class="results-toolbar">
                <span><strong>{{ $trips->total() }} trips found</strong></span>
            </div>

            @if ($trips->isEmpty())
                <div class="text-center py-5">
                    <p class="lead">No trips match your search yet.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($trips as $trip)
                        <div class="col-sm-6 col-xl-4">
                            <x-trip-card :trip="$trip" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $trips->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</main>
@endsection
