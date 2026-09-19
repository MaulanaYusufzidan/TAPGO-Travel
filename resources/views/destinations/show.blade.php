@extends('layouts.app')

@section('title', $destination->name . ' — TAPGO TRAVEL')
@section('meta_description', \Illuminate\Support\Str::limit($destination->description, 150))

@section('content')
    @php
        $fallbackImages = [
            'Bali' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1600&q=80',
            'Yogyakarta' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=1600&q=80',
            'Raja Ampat' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc32?auto=format&fit=crop&w=1600&q=80',
            'Bromo Tengger Semeru' => 'https://images.unsplash.com/photo-1554403050-4a4c580c1d1c?auto=format&fit=crop&w=1600&q=80',
            'Labuan Bajo' => 'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?auto=format&fit=crop&w=1600&q=80',
            'Bandung' => 'https://images.unsplash.com/photo-1596395463119-e1dc75740fb9?auto=format&fit=crop&w=1600&q=80',
        ];
        $heroFallback = $fallbackImages[$destination->name] ?? 'https://images.unsplash.com/photo-1500835556837-99ac94a94552?auto=format&fit=crop&w=1600&q=80';
    @endphp

    <div class="ratio ratio-21x9 bg-secondary-subtle">
        <img src="{{ asset('storage/' . $destination->hero_image) }}"
             alt="{{ $destination->name }}" class="object-fit-cover"
             onerror="this.onerror=null;this.src='{{ $heroFallback }}'">
    </div>

    <main class="marketplace-page pt-4">
    <div class="container">
        <x-breadcrumb :links="[['label' => 'Destinations', 'url' => route('destinations.index')]]" current="{{ $destination->name }}" />

        <div class="row g-5">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-1">{{ $destination->name }}</h1>
                <p class="text-muted mb-4">📍 {{ $destination->location }}</p>

                <p class="mb-4">{{ $destination->description }}</p>

                @if (!empty($destination->things_to_do))
                    <h2 class="h4 fw-semibold mb-3">Things to Do</h2>
                    <div class="row g-2 mb-4">
                        @foreach ($destination->things_to_do as $item)
                            <div class="col-sm-6"><div class="d-flex align-items-start gap-2"><span>✅</span><span>{{ $item }}</span></div></div>
                        @endforeach
                    </div>
                @endif

                @if ($destination->images->isNotEmpty())
                    <h2 class="h4 fw-semibold mb-3">Gallery</h2>
                    <div class="row g-2 mb-4">
                        @foreach ($destination->images as $image)
                            <div class="col-4">
                                <div class="ratio ratio-1x1 bg-secondary-subtle rounded overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $image->caption ?? $destination->name }}" class="object-fit-cover"
                                         onerror="this.onerror=null;this.src='{{ $heroFallback }}'">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <h2 class="h4 fw-semibold mb-3">Popular Trips</h2>
                @if ($trips->isNotEmpty())
                    <div class="row g-4 mb-4">
                        @foreach ($trips as $trip)
                            <div class="col-sm-6">
                                <x-trip-card :trip="$trip" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small">No trip packages for this destination just yet — check back soon.</p>
                @endif
            </div>

            <div class="col-lg-4">
                @if (!empty($destination->travel_information))
                    <div class="detail-panel mb-4">
                        <h2 class="h6 fw-semibold text-uppercase mb-3">Travel Information</h2>
                        <ul class="list-unstyled small mb-0">
                            @foreach ($destination->travel_information as $key => $value)
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="text-muted text-capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                    <strong>{{ $value }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($related->isNotEmpty())
                    <div class="detail-panel">
                        <h2 class="h6 fw-semibold text-uppercase mb-3">Related Destinations</h2>
                        <div class="vstack gap-3">
                            @foreach ($related as $item)
                                <x-destination-card :destination="$item" />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </main>
@endsection
