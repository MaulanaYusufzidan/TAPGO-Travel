<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Models\RoomRatePlan;
use App\Models\RoomType;
use App\Services\HotelAvailabilityService;
use App\Services\HotelBookingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HotelBookingController extends Controller
{
    public function __construct(
        protected HotelAvailabilityService $availability,
        protected HotelBookingService $bookingService,
    ) {
    }

    /**
     * Terima pilihan room type + rate plan + tanggal + jumlah kamar dari
     * halaman hotel detail (spec section 32-34). Cek availability, hitung
     * harga di backend, simpan sementara ke session, lalu lempar ke
     * checkout.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selection' => ['required', 'string'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
        ]);

        // "selection" = "{room_type_id}:{room_rate_plan_id}", dikirim oleh
        // tombol "Select Room" di tiap pilihan rate plan (spec: 2 pilihan
        // harga per kamar, "Your Choice").
        [$roomTypeId, $ratePlanId] = array_pad(explode(':', $validated['selection'], 2), 2, null);

        $roomType = RoomType::with('hotel')->findOrFail($roomTypeId);
        $ratePlan = $ratePlanId ? RoomRatePlan::where('room_type_id', $roomType->id)->findOrFail($ratePlanId) : null;

        $checkIn = Carbon::parse($validated['check_in'])->startOfDay();
        $checkOut = Carbon::parse($validated['check_out'])->startOfDay();

        // Tiap room card di halaman detail punya input quantity sendiri
        // (quantity[room_type_id]), dikirim bersama dalam satu <form>.
        $quantity = (int) $request->input("quantity.{$roomType->id}", 1);

        if ($quantity < 1) {
            return back()->withErrors(['quantity' => 'Jumlah kamar minimal 1.']);
        }

        if ($validated['guests'] > $roomType->max_guests * $quantity) {
            return back()->withErrors([
                'guests' => "Tipe kamar ini maksimal {$roomType->max_guests} tamu per kamar. Tambah jumlah kamar atau kurangi jumlah tamu.",
            ]);
        }

        if (! $this->availability->checkAvailability($roomType, $checkIn, $checkOut, $quantity)) {
            return back()->withErrors([
                'quantity' => 'Maaf, kamar tidak cukup tersedia untuk tanggal yang dipilih.',
            ]);
        }

        $breakdown = $this->bookingService->calculatePrice($roomType, $checkIn, $checkOut, $quantity, $ratePlan);

        session([
            'pending_hotel_booking' => [
                'room_type_id' => $roomType->id,
                'room_rate_plan_id' => $ratePlan?->id,
                'hotel_id' => $roomType->hotel_id,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'guests' => (int) $validated['guests'],
                'quantity' => $quantity,
                'breakdown' => $breakdown,
            ],
        ]);

        return redirect()->route('hotel-checkout.show');
    }

    /**
     * Halaman checkout: review kamar, tanggal, price breakdown, isi data
     * tamu, dan pilih metode pembayaran (spec section 36).
     */
    public function checkout(): View|RedirectResponse
    {
        $pending = session('pending_hotel_booking');

        if (! $pending) {
            return redirect()->route('hotels.index')
                ->with('status', 'Tidak ada pemesanan hotel yang sedang diproses. Silakan pilih kamar terlebih dahulu.');
        }

        $roomType = RoomType::with('hotel')->findOrFail($pending['room_type_id']);
        $ratePlan = $pending['room_rate_plan_id'] ?? null
            ? RoomRatePlan::find($pending['room_rate_plan_id'])
            : null;

        return view('hotel-bookings.checkout', [
            'roomType' => $roomType,
            'ratePlan' => $ratePlan,
            'hotel' => $roomType->hotel,
            'checkIn' => Carbon::parse($pending['check_in']),
            'checkOut' => Carbon::parse($pending['check_out']),
            'guests' => $pending['guests'],
            'quantity' => $pending['quantity'],
            'breakdown' => $pending['breakdown'],
        ]);
    }

    /**
     * Konfirmasi booking: cek ulang availability, reservasi kamar, dan
     * simpan HotelBooking + HotelBookingRoom secara atomic dalam satu
     * database transaction (spec section 46-47: overbooking prevention).
     */
    public function confirm(Request $request): RedirectResponse
    {
        $pending = session('pending_hotel_booking');

        if (! $pending) {
            return redirect()->route('hotels.index');
        }

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:30'],
            'special_request' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:bank_transfer,credit_card,e_wallet,qris'],
        ]);

        $roomType = RoomType::with('hotel')->findOrFail($pending['room_type_id']);
        $ratePlanId = $pending['room_rate_plan_id'] ?? null;
        $checkIn = Carbon::parse($pending['check_in']);
        $checkOut = Carbon::parse($pending['check_out']);
        $quantity = $pending['quantity'];
        $breakdown = $pending['breakdown'];

        if (! $this->availability->checkAvailability($roomType, $checkIn, $checkOut, $quantity)) {
            session()->forget('pending_hotel_booking');

            return redirect()->route('hotels.show', $roomType->hotel)
                ->withErrors(['quantity' => 'Kamar sudah tidak cukup tersedia lagi. Silakan pilih tanggal atau kamar lain.']);
        }

        $booking = DB::transaction(function () use ($roomType, $ratePlanId, $checkIn, $checkOut, $quantity, $breakdown, $pending, $validated) {
            if (! $this->availability->reserve($roomType, $checkIn, $checkOut, $quantity)) {
                return null;
            }

            $booking = HotelBooking::create([
                'booking_code' => 'TPGH-'.strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'hotel_id' => $roomType->hotel_id,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'guests' => $pending['guests'],
                'rooms' => $quantity,
                'subtotal' => $breakdown['subtotal'],
                'tax' => $breakdown['tax'],
                'service_fee' => $breakdown['service_fee'],
                'discount' => $breakdown['discount'],
                'total' => $breakdown['total'],
                'status' => 'awaiting_payment',
                'guest_name' => $validated['guest_name'],
                'guest_email' => $validated['guest_email'],
                'guest_phone' => $validated['guest_phone'],
                'special_request' => $validated['special_request'] ?? null,
            ]);

            $booking->bookingRooms()->create([
                'room_type_id' => $roomType->id,
                'room_rate_plan_id' => $ratePlanId,
                'quantity' => $quantity,
                'price_per_night' => $roomType->base_price,
                'nights' => $breakdown['nights'],
                'subtotal' => $breakdown['subtotal'],
            ]);

            $booking->payments()->create([
                'payment_code' => 'PAYH-'.strtoupper(Str::random(8)),
                'method' => $validated['payment_method'],
                'amount' => $breakdown['total'],
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);

            return $booking;
        });

        if (! $booking) {
            session()->forget('pending_hotel_booking');

            return redirect()->route('hotels.show', $roomType->hotel)
                ->withErrors(['quantity' => 'Kamar sudah tidak cukup tersedia lagi. Silakan pilih tanggal atau kamar lain.']);
        }

        session()->forget('pending_hotel_booking');

        return redirect()
            ->route('hotel-bookings.payment', $booking)
            ->with('status', 'Booking hotel berhasil dibuat! Kode booking: '.$booking->booking_code);
    }

    /**
     * Halaman pembayaran mock (spec section 37) — belum terhubung ke
     * payment gateway asli, cuma simulasi Success/Failed buat kebutuhan
     * demo/tugas.
     */
    public function payment(HotelBooking $hotelBooking): View
    {
        abort_unless($hotelBooking->user_id === Auth::id(), 403);

        $hotelBooking->load('payments');
        $payment = $hotelBooking->payments()->latest()->first();

        return view('hotel-bookings.payment', ['booking' => $hotelBooking, 'payment' => $payment]);
    }

    /**
     * Tombol "Simulate Successful/Failed Payment" — jangan diekspos di
     * production (spec section 37).
     */
    public function simulatePayment(Request $request, HotelBooking $hotelBooking): RedirectResponse
    {
        abort_unless($hotelBooking->user_id === Auth::id(), 403);
        abort_if(app()->environment('production'), 404);

        $request->validate(['result' => ['required', 'in:success,failed']]);

        $payment = $hotelBooking->payments()->latest()->first();

        if ($request->input('result') === 'success') {
            $payment->update(['status' => 'paid', 'paid_at' => now()]);
            $hotelBooking->update(['status' => 'confirmed']);
        } else {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('hotel-bookings.confirmation', $hotelBooking);
    }

    /**
     * Halaman konfirmasi setelah booking dibuat. Integrasi pembayaran
     * menyusul di fase Payment berikutnya — status booking masih 'pending'
     * sampai payment beneran terhubung.
     */
    public function confirmation(HotelBooking $hotelBooking): View
    {
        abort_unless($hotelBooking->user_id === Auth::id(), 403);

        $hotelBooking->load(['hotel', 'bookingRooms.roomType', 'bookingRooms.ratePlan', 'payments']);

        return view('hotel-bookings.confirmation', ['booking' => $hotelBooking]);
    }
}
