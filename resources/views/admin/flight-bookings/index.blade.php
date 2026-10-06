@extends('layouts.admin')

@section('title', 'Flight Bookings — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Flight Bookings</h1>

<form method="GET" action="{{ route('admin.flight-bookings.index') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label for="q" class="form-label small">Cari (kode booking / email kontak / customer)</label>
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
                        <th>Penerbangan</th>
                        <th>Berangkat</th>
                        <th>Penumpang</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        @php $dep = $booking->flightOffer?->departureFlight; @endphp
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td class="small">
                                @if ($dep)
                                    {{ $dep->origin_code }} → {{ $dep->destination_code }}<br>
                                    <span class="text-muted">{{ $dep->airline->name ?? '-' }} {{ $dep->flight_number }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="small">{{ $dep?->departure_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>{{ $booking->passengers }}</td>
                            <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></td>
                            <td class="small text-muted">{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.flight-bookings.show', $booking) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-muted small">Belum ada booking flight yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
