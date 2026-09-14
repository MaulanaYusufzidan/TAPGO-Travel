@extends('layouts.admin')

@section('title', 'Booking ' . $booking->booking_code . ' — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Booking {{ $booking->booking_code }}</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Detail</h2>
                <p class="mb-1"><strong>Customer:</strong> {{ $booking->user->name ?? '-' }} ({{ $booking->user->email ?? '-' }})</p>
                <p class="mb-1"><strong>Trip:</strong> {{ $booking->schedule->trip->title ?? '-' }}</p>
                <p class="mb-1"><strong>Destination:</strong> {{ $booking->schedule->trip->destination->name ?? '-' }}</p>
                <p class="mb-1"><strong>Schedule:</strong> {{ $booking->schedule->date->format('d M Y') }}</p>
                <p class="mb-1"><strong>Quantity:</strong> {{ $booking->quantity }}</p>
                <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($booking->total, 0, ',', '.') }}</p>
                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Travelers</h2>
                <ol class="mb-0">
                    @foreach ($booking->travelers as $traveler)
                        <li>{{ $traveler->full_name }}</li>
                    @endforeach
                </ol>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Payments</h2>
                @forelse ($booking->payments as $payment)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada payment.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Update Status</h2>
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        @foreach (['pending', 'confirmed', 'completed', 'cancelled', 'expired'] as $status)
                            <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                </form>
            </div>
        </div>

        @if (! in_array($booking->status, ['cancelled', 'expired', 'completed']))
            <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}"
                  onsubmit="return confirm('Batalkan booking ini? Kursi akan dikembalikan ke schedule.');">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Cancel Booking</button>
            </form>
        @endif
    </div>
</div>
@endsection
