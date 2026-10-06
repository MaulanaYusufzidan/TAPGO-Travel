@extends('layouts.admin')

@section('title', 'Flight Booking ' . $booking->booking_code . ' — Admin TAPGO TRAVEL')

@section('content')
@php
    $dep = $booking->flightOffer?->departureFlight;
    $ret = $booking->flightOffer?->returnFlight;
@endphp
<p class="mb-2"><a href="{{ route('admin.flight-bookings.index') }}" class="small">&larr; Kembali ke Flight Bookings</a></p>
<h1 class="h4 fw-bold mb-4">Flight Booking {{ $booking->booking_code }}</h1>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Penerbangan</h2>
                <p class="mb-2"><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></p>
                @if ($dep)
                    <p class="mb-1"><strong>Berangkat:</strong> {{ $dep->origin_city }} ({{ $dep->origin_code }}) → {{ $dep->destination_city }} ({{ $dep->destination_code }})</p>
                    <p class="mb-1 small text-muted">{{ $dep->airline->name ?? '-' }} {{ $dep->flight_number }} · {{ $dep->travel_class }} · {{ $dep->departure_at->format('d M Y H:i') }} – {{ $dep->arrival_at->format('H:i') }}</p>
                @else
                    <p class="small text-muted">Data penerbangan tidak tersedia.</p>
                @endif
                @if ($ret)
                    <p class="mb-1 mt-2"><strong>Pulang:</strong> {{ $ret->origin_city }} ({{ $ret->origin_code }}) → {{ $ret->destination_city }} ({{ $ret->destination_code }})</p>
                    <p class="mb-0 small text-muted">{{ $ret->airline->name ?? '-' }} {{ $ret->flight_number }} · {{ $ret->travel_class }} · {{ $ret->departure_at->format('d M Y H:i') }} – {{ $ret->arrival_at->format('H:i') }}</p>
                @endif
                <p class="mb-0 mt-2 small text-muted">Dibuat {{ $booking->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Penumpang ({{ $booking->passengers }})</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Nama</th><th>Gender</th><th>Kewarganegaraan</th><th>Paspor</th></tr></thead>
                        <tbody>
                            @forelse ($booking->passengerDetails as $p)
                                <tr>
                                    <td>{{ $p->full_name }}</td>
                                    <td>{{ ucfirst($p->gender) }}</td>
                                    <td>{{ $p->nationality }}</td>
                                    <td class="text-muted">••••{{ substr($p->passport_number, -3) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted small">Belum ada data penumpang.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Pembayaran</h2>
                @forelse ($booking->payments as $payment)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $payment->payment_code }} · {{ str($payment->method)->headline() }}</span>
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }} <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span></span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada pembayaran.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Customer</h2>
                <p class="mb-1">{{ $booking->user->name ?? '-' }}</p>
                <p class="mb-3 small text-muted">{{ $booking->user->email ?? '-' }}</p>
                <h2 class="h6 fw-semibold mb-2">Kontak Booking</h2>
                <p class="mb-0 small text-muted">{{ $booking->contact_email }} · {{ $booking->contact_phone }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Rincian Harga</h2>
                <div class="d-flex justify-content-between small mb-1"><span>Subtotal</span><span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between small mb-1"><span>Pajak</span><span>Rp {{ number_format($booking->tax, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between small mb-1"><span>Service fee</span><span>Rp {{ number_format($booking->service_fee, 0, ',', '.') }}</span></div>
                @if ($booking->discount > 0)
                    <div class="d-flex justify-content-between small mb-1"><span>Diskon</span><span>- Rp {{ number_format($booking->discount, 0, ',', '.') }}</span></div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>Rp {{ number_format($booking->total, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
