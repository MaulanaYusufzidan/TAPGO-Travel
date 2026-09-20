@extends('layouts.app')

@section('title', 'Invoice ' . $booking->booking_code . ' — TAPGO TRAVEL')

@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="{{ route('bookings.index') }}" class="small text-decoration-none">&larr; Pesanan Saya</a>
        <button type="button" class="btn btn-primary" onclick="window.print()">🖨️ Download / Print Invoice</button>
    </div>

    <div class="detail-panel" id="invoice-paper">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <p class="fw-bold fs-4 mb-0" style="color:#1968e0;">TAPGO <span style="color:#17233b;">Travel</span></p>
                <p class="text-muted small mb-0">Jakarta, Indonesia</p>
                <p class="text-muted small mb-0">hello@tapgotravel.com</p>
            </div>
            <div class="text-end">
                <p class="h5 fw-bold mb-1">INVOICE</p>
                <p class="mb-0"><strong>No:</strong> {{ $booking->booking_code }}</p>
                <p class="mb-0 text-muted small">Tanggal: {{ $payment->paid_at?->format('d M Y') ?? $booking->created_at->format('d M Y') }}</p>
                <span class="rating d-inline-block mt-1">LUNAS</span>
            </div>
        </div>

        <hr>

        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <p class="small fw-bold text-uppercase text-muted mb-1">Ditagihkan kepada</p>
                <p class="mb-0 fw-bold">{{ $booking->user->name }}</p>
                <p class="mb-0 text-muted">{{ $booking->user->email }}</p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <p class="small fw-bold text-uppercase text-muted mb-1">Detail Perjalanan</p>
                <p class="mb-0 fw-bold">{{ $booking->schedule->trip->title }}</p>
                <p class="mb-0 text-muted">{{ $booking->schedule->trip->destination->name }} · {{ $booking->schedule->date->translatedFormat('d M Y') }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr class="border-bottom">
                    <th>Deskripsi</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $booking->schedule->trip->title }}</td>
                    <td class="text-end">{{ $booking->quantity }}</td>
                    <td class="text-end">Rp {{ number_format($booking->price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($booking->price * $booking->quantity, 0, ',', '.') }}</td>
                </tr>
                @if ($booking->service_fee > 0)
                    <tr>
                        <td colspan="3" class="text-end text-muted">Biaya Layanan</td>
                        <td class="text-end">Rp {{ number_format($booking->service_fee, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($booking->discount > 0)
                    <tr>
                        <td colspan="3" class="text-end text-danger">Diskon</td>
                        <td class="text-end text-danger">- Rp {{ number_format($booking->discount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="border-top">
                    <td colspan="3" class="text-end fw-bold">Total Dibayar</td>
                    <td class="text-end fw-bold" style="color:#1968e0;">Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="row g-4 mt-3">
            <div class="col-sm-6">
                <p class="small fw-bold text-uppercase text-muted mb-1">Metode Pembayaran</p>
                <p class="mb-0">{{ $payment->method ? strtoupper($payment->method) : 'Midtrans' }}</p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <p class="small fw-bold text-uppercase text-muted mb-1">ID Transaksi</p>
                <p class="mb-0">{{ $payment->transaction_id ?? '-' }}</p>
            </div>
        </div>

        <hr class="mt-4">
        <p class="small text-muted text-center mb-0">Terima kasih telah memesan perjalanan bersama TAPGO Travel.</p>
    </div>
</div>
</main>

<style>
@media print {
    .navbar, footer, .no-print { display: none !important; }
    #invoice-paper { border: none !important; box-shadow: none !important; }
    body { background: #fff !important; }
}
</style>
@endsection
