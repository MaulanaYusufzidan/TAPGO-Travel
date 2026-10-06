@extends('layouts.admin')

@section('title', 'Hotel Bookings — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Hotel Bookings</h1>

<form method="GET" action="{{ route('admin.hotel-bookings.index') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label for="q" class="form-label small">Cari (kode booking / nama tamu / customer)</label>
        <input type="text" id="q" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <label for="status" class="form-label small">Status</label>
        <select id="status" name="status" class="form-select">
            <option value="">Semua</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
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
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Hotel</th>
                        <th>Check in / out</th>
                        <th>Tamu</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td>{{ $booking->hotel->name ?? '-' }}</td>
                            <td class="small">{{ $booking->check_in->format('d M Y') }} — {{ $booking->check_out->format('d M Y') }}</td>
                            <td>{{ $booking->guests }}</td>
                            <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></td>
                            <td class="small text-muted">{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.hotel-bookings.show', $booking) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-muted small">Belum ada booking hotel yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
