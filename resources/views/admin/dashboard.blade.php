@extends('layouts.admin')

@section('title', 'Dashboard — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Dashboard</h1>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Total Revenue</p>
                <p class="h4 fw-bold mb-0">Rp {{ number_format($kpi['total_revenue'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Total Bookings</p>
                <p class="h4 fw-bold mb-0">{{ $kpi['total_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Customers</p>
                <p class="h4 fw-bold mb-0">{{ $kpi['total_customers'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Trips</p>
                <p class="h4 fw-bold mb-0">{{ $kpi['total_trips'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Pending Payments</p>
                <p class="h4 fw-bold mb-0">{{ $kpi['pending_payments'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Monthly Revenue</h2>
                @forelse ($monthlyRevenue as $row)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $row->month }}</span>
                        <span>Rp {{ number_format($row->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Monthly Booking</h2>
                @forelse ($monthlyBooking as $row)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $row->month }}</span>
                        <span>{{ $row->total }} booking</span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Popular Destinations</h2>
                @forelse ($popularDestinations as $row)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $row->name }}</span>
                        <span>{{ $row->bookings_count }} booking</span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Popular Trips</h2>
                @forelse ($popularTrips as $row)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $row->title }}</span>
                        <span>{{ $row->bookings_count }} booking</span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h6 fw-semibold mb-3">Recent Booking</h2>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Customer</th>
                        <th>Trip</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td>{{ $booking->schedule->trip->title ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span></td>
                            <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted small">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
