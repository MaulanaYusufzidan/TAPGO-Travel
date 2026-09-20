@extends('layouts.admin')

@section('title', 'Customers — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Customers</h1>

<form method="GET" action="{{ route('admin.customers.index') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label class="form-label small">Cari (nama / email)</label>
        <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}">
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Bergabung</th>
                        <th>Jumlah Booking</th>
                        <th>Total Dibayar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->created_at->format('d M Y') }}</td>
                            <td>{{ $customer->bookings_count }}</td>
                            <td>Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted small">Belum ada customer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $customers->links() }}</div>
    </div>
</div>
@endsection
