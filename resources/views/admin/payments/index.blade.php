@extends('layouts.admin')

@section('title', 'Payments — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Payments</h1>

<form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label class="form-label small">Cari (booking code / transaction id)</label>
        <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
            <option value="">Semua</option>
            @foreach (['pending', 'paid', 'failed', 'expired', 'refunded'] as $status)
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
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->booking->booking_code ?? '-' }}</td>
                            <td>{{ $payment->booking->user->name ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->method ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-outline-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted small">Belum ada payment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $payments->links() }}</div>
    </div>
</div>
@endsection
