@extends('layouts.app')
@section('title', $flight['airline'].' '.$flight['number'].' — TAPGO Travel')
@section('meta_description', 'Detail penerbangan '.$flight['airline'].' '.$flight['number'].' dari '.$flight['fromCity'].' ke '.$flight['toCity'].'.')
@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb :links="[['label' => 'Flights', 'url' => route('flights.index')]]" current="{{ $flight['airline'] }} {{ $flight['number'] }}" />
    <div class="row g-4">
        <div class="col-lg-8">
            <section class="detail-panel">
                <p class="eyebrow">{{ $flight['airline'] }} · {{ $flight['number'] }}</p>
                <h1>{{ $flight['fromCity'] }} to {{ $flight['toCity'] }}</h1>
                <div class="flight-timeline">
                    <div><strong>{{ $flight['depart'] }}</strong><span>{{ $flight['from'] }}</span><small>Soekarno-Hatta International Airport</small></div>
                    <div class="timeline-line"><span>{{ $flight['duration'] }}</span><i></i><small>{{ $flight['stops'] }}</small></div>
                    <div><strong>{{ $flight['arrive'] }}</strong><span>{{ $flight['to'] }}</span><small>Ngurah Rai International Airport</small></div>
                </div>
                <div class="amenity-icons mt-3">
                    @foreach($flight['amenities'] as $amenity)<span>{{ $amenity }}</span>@endforeach
                </div>
            </section>

            <section class="detail-panel">
                <h2>Flight information</h2>
                <div class="info-grid">
                    <div><strong>Cabin baggage</strong><span>1 cabin bag, up to 7 kg</span></div>
                    <div><strong>Checked baggage</strong><span>20 kg included</span></div>
                    <div><strong>Aircraft</strong><span>Boeing 737-800</span></div>
                    <div><strong>Check-in</strong><span>Available 24 hours before departure</span></div>
                </div>
            </section>

            <section class="detail-panel">
                <h2>Choose your fare</h2>
                @foreach([['Economy', $flight['price'], '20 kg baggage · Standard seat selection · Changes with fee'],['Business', number_format(((float) str_replace('.', '', $flight['price'])) * 2.2, 0, ',', '.'), '30 kg baggage · Priority service · Flexible changes']] as [$fare,$price,$notes])
                    <div class="fare-row">
                        <div><strong>{{ $fare }}</strong><p>{{ $notes }}</p></div>
                        <div class="d-flex align-items-center"><strong>Rp {{ $price }}</strong>
                            <a href="{{ route('contact', ['subject' => 'Flight enquiry: ' . $flight['airline'] . ' ' . $flight['number'] . ' (' . $fare . ')']) }}" class="btn btn-outline-primary btn-sm ms-3">Select</a>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
        <aside class="col-lg-4">
            <div class="booking-card">
                <p class="eyebrow">Your flight</p>
                <h3>{{ $flight['from'] }} → {{ $flight['to'] }}</h3>
                <p class="text-muted small">{{ $flight['airline'] }} {{ $flight['number'] }} · 1 Adult</p>
                <hr>
                @if(($flight['original_price'] ?? $flight['price']) !== $flight['price'])
                    <div class="d-flex justify-content-between text-muted small mb-1"><span>Was</span><span class="price-was">Rp {{ $flight['original_price'] }}</span></div>
                @endif
                <div class="d-flex justify-content-between"><span>Total</span><strong>Rp {{ $flight['price'] }}</strong></div>
                <a href="{{ route('contact', ['subject' => 'Flight enquiry: ' . $flight['airline'] . ' ' . $flight['number']]) }}" class="btn btn-primary w-100 mt-3">Enquire to Book</a>
            </div>
        </aside>
    </div>
</div>
</main>
@endsection
