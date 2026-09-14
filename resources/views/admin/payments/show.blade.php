@extends('layouts.admin')

@section('title', 'Payment #' . $payment->id . ' — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Payment #{{ $payment->id }}</h1>

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
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Detail</h2>
                <p class="mb-1"><strong>Booking:</strong> {{ $payment->booking->booking_code ?? '-' }}</p>
                <p class="mb-1"><strong>Customer:</strong> {{ $payment->booking->user->name ?? '-' }}</p>
                <p class="mb-1"><strong>Trip:</strong> {{ $payment->booking->schedule->trip->title ?? '-' }}</p>
                <p class="mb-1"><strong>Amount:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p class="mb-1"><strong>Method:</strong> {{ $payment->method ?? '-' }}</p>
                <p class="mb-1"><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? '-' }}</p>
                <p class="mb-1"><strong>Paid At:</strong> {{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}</p>
                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @if ($payment->status !== 'paid')
            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}"
                  onsubmit="return confirm('Verifikasi payment ini secara manual sebagai paid?');">
                @csrf
                <button type="submit" class="btn btn-primary w-100">Verify as Paid (Manual)</button>
            </form>
        @else
            <p class="small text-muted">Payment ini sudah terverifikasi paid.</p>
        @endif
    </div>
</div>
@endsection
