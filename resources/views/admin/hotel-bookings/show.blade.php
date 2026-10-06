@extends('layouts.admin')

@section('title', 'Hotel Booking ' . $booking->booking_code . ' — Admin TAPGO TRAVEL')

@section('content')
<p class="mb-2"><a href="{{ route('admin.hotel-bookings.index') }}" class="small">&larr; Kembali ke Hotel Bookings</a></p>
<h1 class="h4 fw-bold mb-4">Hotel Booking {{ $booking->booking_code }}</h1>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Detail Booking</h2>
                <p class="mb-1"><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></p>
                <p class="mb-1"><strong>Hotel:</strong> {{ $booking->hotel->name ?? '-' }}</p>
                <p class="mb-1"><strong>Check in:</strong> {{ $booking->check_in->format('d M Y') }}</p>
                <p class="mb-1"><strong>Check out:</strong> {{ $booking->check_out->format('d M Y') }}</p>
                <p class="mb-1"><strong>Jumlah tamu:</strong> {{ $booking->guests }} · <strong>Kamar:</strong> {{ $booking->rooms }}</p>
                @if ($booking->special_request)
                    <p class="mb-1"><strong>Permintaan khusus:</strong> {{ $booking->special_request }}</p>
                @endif
                <p class="mb-0 small text-muted">Dibuat {{ $booking->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Kamar</h2>
                @forelse ($booking->bookingRooms as $room)
                    <p class="small mb-1">
                        {{ $room->quantity }}x {{ $room->roomType->name ?? '-' }}{{ $room->ratePlan ? ' ('.$room->ratePlan->name.')' : '' }}
                        — {{ $room->nights }} malam, Rp {{ number_format($room->subtotal, 0, ',', '.') }}
                    </p>
                @empty
                    <p class="small text-muted mb-0">Tidak ada data kamar.</p>
                @endforelse
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Pembayaran</h2>
                @forelse ($booking->payments as $payment)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $payment->payment_code }} · {{ str($payment->method)->headline() }}</span>
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }} <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span></span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">Belum ada pembayaran.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Customer</h2>
                <p class="mb-1">{{ $booking->user->name ?? '-' }}</p>
                <p class="mb-3 small text-muted">{{ $booking->user->email ?? '-' }}</p>
                <h2 class="h6 fw-semibold mb-2">Kontak Tamu</h2>
                <p class="mb-1">{{ $booking->guest_name }}</p>
                <p class="mb-0 small text-muted">{{ $booking->guest_email }} · {{ $booking->guest_phone }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-semibold mb-3">Rincian Harga</h2>
                <div class="d-flex justify-content-between small mb-1"><span>Subtotal</span><span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between small mb-1"><span>Pajak</span><span>Rp {{ number_format($booking->tax, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between small mb-1"><span>Service fee</span><span>Rp {{ number_format($booking->service_fee, 0, ',', '.') }}</span></div>
                @if ($booking->discount > 0)
                    <div class="d-flex justify-content-between small mb-1"><span>Diskon</span><span>- Rp {{ number_format($booking->discount, 0, ',', '.') }}</span></div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>Rp {{ number_format($booking->total, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
