@extends('layouts.admin')

@section('title', 'Bookings — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Bookings</h1>

<form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label class="form-label small">Cari (booking code / customer)</label>
        <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
            <option value="">Semua</option>
            @foreach (['pending', 'confirmed', 'completed', 'cancelled', 'expired'] as $status)
                <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Customer</th>
                        <th>Trip</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td>{{ $booking->schedule->trip->title ?? '-' }}</td>
                            <td>{{ $booking->quantity }}</td>
                            <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted small">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
