@extends('layouts.app')

@section('title', 'Trips — TAPGO TRAVEL')
@section('meta_description', 'Jelajahi paket perjalanan terbaik ke seluruh Indonesia bersama TAPGO TRAVEL.')

@section('content')
<div class="container py-5 py-lg-6">
    <p class="eyebrow mb-2">Find your next experience</p>
    <h1 class="fw-bold mb-2">Explore trips across Indonesia</h1>
    <p class="text-muted mb-4">Compare locally curated journeys and book when the timing feels right.</p>

    <form method="GET" action="{{ route('trips.index') }}" class="row g-3 align-items-end mb-5 p-3 p-lg-4 bg-light rounded-3">
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
        <p class="small text-muted mb-3">{{ $trips->total() }} experiences found</p>
        <div class="row g-4">
            @foreach ($trips as $trip)
                <div class="col-md-4">
                    <x-trip-card :trip="$trip" />
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $trips->links() }}
        </div>
    @endif
</div>
@endsection
