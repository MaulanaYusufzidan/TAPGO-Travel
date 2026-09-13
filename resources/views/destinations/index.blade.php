@extends('layouts.app')

@section('title', 'Destinations — TAPGO TRAVEL')
@section('meta_description', 'Jelajahi destinasi wisata terbaik di Indonesia bersama TAPGO TRAVEL.')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Destinations</h1>

    <form method="GET" action="{{ route('destinations.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-5">
            <label for="q" class="form-label small fw-semibold text-uppercase text-muted">Cari</label>
            <input type="text" name="q" id="q" class="form-control" placeholder="Nama atau lokasi..." value="{{ $filters['q'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <label for="location" class="form-label small fw-semibold text-uppercase text-muted">Lokasi</label>
            <select name="location" id="location" class="form-select">
                <option value="">Semua Lokasi</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc }}" @selected(($filters['location'] ?? '') === $loc)>{{ $loc }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="sort" class="form-label small fw-semibold text-uppercase text-muted">Urutkan</label>
            <select name="sort" id="sort" class="form-select">
                <option value="recommended" @selected(($filters['sort'] ?? 'recommended') === 'recommended')>Rekomendasi</option>
                <option value="name_asc" @selected(($filters['sort'] ?? '') === 'name_asc')>Nama A-Z</option>
                <option value="name_desc" @selected(($filters['sort'] ?? '') === 'name_desc')>Nama Z-A</option>
                <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Terbaru</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </div>
    </form>

    @if ($destinations->isEmpty())
        <div class="text-center py-5">
            <p class="lead">Tidak ada destinasi yang cocok dengan pencarianmu.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach ($destinations as $destination)
                <div class="col-md-4">
                    <div class="card h-100 border-0">
                        <div class="ratio ratio-4x3 bg-secondary-subtle rounded-top overflow-hidden">
                            <img src="{{ asset('storage/' . $destination->hero_image) }}"
                                 alt="{{ $destination->name }}" class="object-fit-cover">
                        </div>
                        <div class="card-body">
                            @if ($destination->is_featured)
                                <span class="badge bg-warning text-dark mb-2">Populer</span>
                            @endif
                            <h5 class="card-title fw-bold mb-1">{{ $destination->name }}</h5>
                            <p class="text-muted small mb-2">{{ $destination->location }}</p>
                            <p class="card-text small">{{ \Illuminate\Support\Str::limit($destination->description, 90) }}</p>
                            <a href="{{ url('/destinations/' . $destination->slug) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $destinations->links() }}
        </div>
    @endif
</div>
@endsection
