@extends('layouts.app')

@section('title', 'Trips — TAPGO TRAVEL')
@section('meta_description', 'Jelajahi paket perjalanan terbaik ke seluruh Indonesia bersama TAPGO TRAVEL.')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Trips</h1>

    <form method="GET" action="{{ route('trips.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label for="q" class="form-label small fw-semibold text-uppercase text-muted">Cari</label>
            <input type="text" name="q" id="q" class="form-control" placeholder="Nama trip..." value="{{ $filters['q'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label for="destination" class="form-label small fw-semibold text-uppercase text-muted">Destinasi</label>
            <select name="destination" id="destination" class="form-select">
                <option value="">Semua</option>
                @foreach ($destinations as $d)
                    <option value="{{ $d->slug }}" @selected(($filters['destination'] ?? '') === $d->slug)>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="category" class="form-label small fw-semibold text-uppercase text-muted">Kategori</label>
            <select name="category" id="category" class="form-select">
                <option value="">Semua</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->slug }}" @selected(($filters['category'] ?? '') === $c->slug)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="sort" class="form-label small fw-semibold text-uppercase text-muted">Urutkan</label>
            <select name="sort" id="sort" class="form-select">
                <option value="recommended" @selected(($filters['sort'] ?? 'recommended') === 'recommended')>Rekomendasi</option>
                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Harga Terendah</option>
                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Harga Tertinggi</option>
                <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Rating</option>
                <option value="popularity" @selected(($filters['sort'] ?? '') === 'popularity')>Popularitas</option>
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </div>
    </form>

    @if ($trips->isEmpty())
        <div class="text-center py-5">
            <p class="lead">Tidak ada trip yang cocok dengan pencarianmu.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach ($trips as $trip)
                <div class="col-md-4">
                    <div class="card h-100 border-0">
                        <div class="ratio ratio-4x3 bg-secondary-subtle rounded-top overflow-hidden">
                            @if ($trip->images->first())
                                <img src="{{ asset('storage/' . $trip->images->first()->image_path) }}"
                                     alt="{{ $trip->title }}" class="object-fit-cover">
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($trip->is_featured)
                                <span class="badge bg-warning text-dark mb-2">Populer</span>
                            @endif
                            <h5 class="card-title fw-bold mb-1">{{ $trip->title }}</h5>
                            <p class="text-muted small mb-1">
                                {{ $trip->destination->name }} &middot; {{ $trip->duration }}
                            </p>
                            <p class="small mb-2">
                                &#9733; {{ number_format($trip->rating_avg, 1) }} ({{ $trip->reviews_count }} reviews)
                            </p>
                            <p class="fw-semibold text-primary mb-2">
                                Rp {{ number_format($trip->base_price, 0, ',', '.') }}
                            </p>
                            <a href="{{ url('/trips/' . $trip->slug) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $trips->links() }}
        </div>
    @endif
</div>
@endsection
