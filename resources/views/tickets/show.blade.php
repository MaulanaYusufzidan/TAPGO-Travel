@extends('layouts.app')

@section('title', 'E-Ticket ' . $ticket['booking_code'] . ' — TAPGO TRAVEL')

@push('styles')
<style>
    @media print {
        header, footer, .no-print { display: none !important; }
        body { background: #fff !important; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="fw-bold text-primary fs-4 mb-0">TAPGO TRAVEL</p>
                            <p class="text-muted small mb-0">Discover More. Travel Better.</p>
                        </div>
                        <span class="badge bg-success">{{ ucfirst($ticket['payment_status']) }}</span>
                    </div>

                    <hr>

                    <div class="row g-4 mb-4">
                        <div class="col-md-7">
                            <p class="small text-muted mb-1">Booking Code</p>
                            <p class="fw-bold fs-5 mb-3">{{ $ticket['booking_code'] }}</p>

                            <p class="small text-muted mb-1">Trip</p>
                            <p class="fw-semibold mb-3">{{ $ticket['trip_title'] }} — {{ $ticket['destination_name'] }}</p>

                            <p class="small text-muted mb-1">Travel Date</p>
                            <p class="mb-3">
                                {{ $ticket['travel_date']->translatedFormat('d M Y') }}
                                @if ($ticket['departure_time'])
                                    &middot; Berangkat {{ \Illuminate\Support\Carbon::parse($ticket['departure_time'])->format('H:i') }}
                                @endif
                                @if ($ticket['return_time'])
                                    &middot; Kembali {{ \Illuminate\Support\Carbon::parse($ticket['return_time'])->format('H:i') }}
                                @endif
                            </p>

                            @if ($ticket['meeting_point'])
                                <p class="small text-muted mb-1">Meeting Point</p>
                                <p class="mb-3">{{ $ticket['meeting_point'] }}</p>
                            @endif

                            <p class="small text-muted mb-1">Customer</p>
                            <p class="mb-3">{{ $ticket['customer_name'] }}</p>

                            <p class="small text-muted mb-1">Travelers ({{ count($ticket['travelers']) }})</p>
                            <ul class="mb-0">
                                @foreach ($ticket['travelers'] as $name)
                                    <li>{{ $name }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-md-5 text-center">
                            <div class="border rounded-3 p-3 d-inline-block">
                                {!! $qrSvg !!}
                            </div>
                            <p class="small text-muted mt-2 mb-0">Tunjukkan QR ini saat check-in</p>
                        </div>
                    </div>

                    <hr>

                    <button type="button" class="btn btn-outline-primary no-print" onclick="window.print()">
                        Cetak Tiket
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
