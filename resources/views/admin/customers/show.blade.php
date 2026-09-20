@extends('layouts.admin')

@section('title', $customer->name . ' — Admin TAPGO TRAVEL')

@section('content')
<a href="{{ route('admin.customers.index') }}" class="small text-decoration-none">&larr; Kembali ke Customers</a>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 my-3">
    <div>
        <h1 class="h4 fw-bold mb-1">{{ $customer->name }}</h1>
        <p class="text-muted mb-0">{{ $customer->email }} · Bergabung {{ $customer->created_at->format('d M Y') }}</p>
    </div>
    <div class="text-end">
        <p class="text-muted small mb-0">Total dibayar</p>
        <p class="h5 fw-bold mb-0">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h6 fw-bold mb-3">Riwayat Booking ({{ $customer->bookings->count() }})</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Trip</th>
                        <th>Jadwal</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->schedule->trip->title ?? '-' }}</td>
                            <td>{{ $booking->schedule?->date?->format('d M Y') }}</td>
                            <td>{{ $booking->quantity }}</td>
                            <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span></td>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($booking->payments->last()->status ?? '-') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted small">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
