@extends('layouts.app')

@section('title', 'Destinations — TAPGO TRAVEL')
@section('meta_description', 'Jelajahi destinasi wisata terbaik di Indonesia bersama TAPGO TRAVEL.')

@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="Destinations" />
    <div class="page-title-row">
        <div>
            <h1>Discover extraordinary places</h1>
            <p>From cultural heartlands to islands at the edge of the map.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('destinations.index') }}" class="compact-search row g-2 align-items-end">
        <div class="col-md-5">
            <label for="q">Search</label>
            <input type="text" name="q" id="q" class="form-control" placeholder="Name or location..." value="{{ $filters['q'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <label for="sort">Sort by</label>
            <select name="sort" id="sort" class="form-select">
                <option value="recommended" @selected(($filters['sort'] ?? 'recommended') === 'recommended')>Recommended</option>
                <option value="name_asc" @selected(($filters['sort'] ?? '') === 'name_asc')>Name A-Z</option>
                <option value="name_desc" @selected(($filters['sort'] ?? '') === 'name_desc')>Name Z-A</option>
                <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Newest</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="location">Location</label>
            <select name="location" id="location" class="form-select">
                <option value="">All</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc }}" @selected(($filters['location'] ?? '') === $loc)>{{ $loc }}</option>
                @endforeach
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
                    <a href="{{ route('destinations.index') }}" class="btn btn-link btn-sm p-0">Clear all</a>
                </div>
                <div class="filter-group">
                    <h3>Location</h3>
                    @foreach ($locations as $loc)
                        <label>
                            <a href="{{ route('destinations.index', array_merge($filters, ['location' => $loc])) }}"
                               class="text-decoration-none {{ ($filters['location'] ?? '') === $loc ? 'fw-bold text-primary' : 'text-body' }}">
                                {{ $loc }}
                            </a>
                        </label>
                    @endforeach
                </div>
                <div class="filter-group">
                    <h3>Featured</h3>
                    <p class="small text-muted mb-0">Handpicked destinations are marked with a badge across the listing and homepage.</p>
                </div>
            </aside>
        </div>

        <div class="col-lg-9">
            @if ($destinations->isEmpty())
                <div class="text-center py-5">
                    <p class="lead">No destinations match your search yet.</p>
                </div>
            @else
                <div class="results-toolbar">
                    <span><strong>{{ $destinations->total() }} destinations found</strong></span>
                </div>
                <div class="row g-4">
                    @foreach ($destinations as $destination)
                        <div class="col-sm-6 col-xl-4">
                            <x-destination-card :destination="$destination" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $destinations->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</main>
@endsection
