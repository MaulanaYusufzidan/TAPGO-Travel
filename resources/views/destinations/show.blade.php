@extends('layouts.app')

@section('title', $destination->name . ' — TAPGO TRAVEL')
@section('meta_description', \Illuminate\Support\Str::limit($destination->description, 150))

@section('content')
    <div class="ratio ratio-21x9 bg-secondary-subtle">
        <img src="{{ asset('storage/' . $destination->hero_image) }}"
             alt="{{ $destination->name }}" class="object-fit-cover">
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-1">{{ $destination->name }}</h1>
                <p class="text-muted mb-4">{{ $destination->location }}</p>

                <p class="mb-4">{{ $destination->description }}</p>

                @if (!empty($destination->things_to_do))
                    <h2 class="h4 fw-semibold mb-3">Things to Do</h2>
                    <ul class="mb-4">
                        @foreach ($destination->things_to_do as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($destination->images->isNotEmpty())
                    <h2 class="h4 fw-semibold mb-3">Gallery</h2>
                    <div class="row g-2 mb-4">
                        @foreach ($destination->images as $image)
                            <div class="col-4">
                                <div class="ratio ratio-1x1 bg-secondary-subtle rounded overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $image->caption ?? $destination->name }}" class="object-fit-cover">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <h2 class="h4 fw-semibold mb-3">Popular Trips</h2>
                <p class="text-muted small">Trip untuk destinasi ini akan tampil di sini setelah fase Trip Package dibangun.</p>
            </div>

            <div class="col-lg-4">
                @if (!empty($destination->travel_information))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h6 fw-semibold text-uppercase mb-3">Travel Information</h2>
                            <ul class="list-unstyled small mb-0">
                                @foreach ($destination->travel_information as $key => $value)
                                    <li class="mb-2">
                                        <span class="text-muted text-capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                        <strong>{{ $value }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($related->isNotEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h6 fw-semibold text-uppercase mb-3">Related Destinations</h2>
                            @foreach ($related as $item)
                                <a href="{{ route('destinations.show', $item) }}"
                                   class="d-block mb-2 text-decoration-none">{{ $item->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
