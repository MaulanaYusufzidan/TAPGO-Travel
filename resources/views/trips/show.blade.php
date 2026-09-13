@extends('layouts.app')

@section('title', $trip->title . ' — TAPGO TRAVEL')
@section('meta_description', \Illuminate\Support\Str::limit($trip->description, 150))

@section('content')
    <div class="ratio ratio-21x9 bg-secondary-subtle">
        @if ($trip->images->first())
            <img src="{{ asset('storage/' . $trip->images->first()->image_path) }}"
                 alt="{{ $trip->title }}" class="object-fit-cover">
        @endif
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                @if ($trip->category)
                    <span class="badge bg-primary-subtle text-primary mb-2">{{ $trip->category->name }}</span>
                @endif
                <h1 class="fw-bold mb-1">{{ $trip->title }}</h1>
                <p class="text-muted mb-2">
                    <a href="{{ route('destinations.show', $trip->destination) }}" class="text-decoration-none">{{ $trip->destination->name }}</a>
                </p>
                <p class="mb-4">
                    &#9733; {{ number_format($trip->rating_avg, 1) }} ({{ $trip->reviews_count }} reviews)
                    &middot; {{ $trip->duration }}
                    @if ($trip->min_group_size || $trip->max_group_size)
                        &middot; {{ $trip->min_group_size }}-{{ $trip->max_group_size }} orang
                    @endif
                </p>

                <p class="mb-4">{{ $trip->description }}</p>

                @if ($trip->meeting_point)
                    <p class="mb-4"><strong>Meeting Point:</strong> {{ $trip->meeting_point }}</p>
                @endif

                @if ($trip->images->count() > 1)
                    <h2 class="h4 fw-semibold mb-3">Gallery</h2>
                    <div class="row g-2 mb-4">
                        @foreach ($trip->images->skip(1) as $image)
                            <div class="col-4">
                                <div class="ratio ratio-1x1 bg-secondary-subtle rounded overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $image->caption ?? $trip->title }}" class="object-fit-cover">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($trip->itineraries->isNotEmpty())
                    <h2 class="h4 fw-semibold mb-3">Itinerary</h2>
                    <ul class="list-unstyled mb-4">
                        @foreach ($trip->itineraries as $day)
                            <li class="mb-2">
                                <strong>Day {{ $day->day_number }}: {{ $day->title }}</strong>
                                @if ($day->description)
                                    <p class="small text-muted mb-0">{{ $day->description }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="row">
                    @if ($trip->inclusions->isNotEmpty())
                        <div class="col-md-6">
                            <h2 class="h5 fw-semibold mb-3">Included</h2>
                            <ul>
                                @foreach ($trip->inclusions as $inc)
                                    <li>{{ $inc->item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($trip->exclusions->isNotEmpty())
                        <div class="col-md-6">
                            <h2 class="h5 fw-semibold mb-3">Excluded</h2>
                            <ul>
                                @foreach ($trip->exclusions as $exc)
                                    <li>{{ $exc->item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Mulai dari</p>
                        <p class="h3 fw-bold text-primary mb-3">Rp {{ number_format($trip->base_price, 0, ',', '.') }}</p>

                        <p class="small text-muted mb-3">
                            Jadwal &amp; ketersediaan akan tampil di sini setelah fase Schedule &amp; Booking dibangun.
                        </p>

                        <button type="button" class="btn btn-primary w-100" disabled>
                            Book Now (segera hadir)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
