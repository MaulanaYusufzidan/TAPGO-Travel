<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected AvailabilityService $availability,
        protected BookingService $bookingService,
        protected PaymentService $paymentService,
    ) {
    }

    /**
     * Tampilkan form input data traveler.
     * PRD section 8 (Core User Journey): Select Schedule -> Select
     * Travelers -> Traveler Information.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $schedule = Schedule::with('trip')->findOrFail($validated['schedule_id']);

        if (! $this->availability->checkAvailability($schedule, (int) $validated['quantity'])) {
            return redirect()
                ->route('trips.show', $schedule->trip)
                ->withErrors(['quantity' => 'Kuota tidak cukup. Sisa kursi: '.$schedule->available_seats.'.']);
        }

        return view('bookings.create', [
            'schedule' => $schedule,
            'quantity' => (int) $validated['quantity'],
        ]);
    }

    /**
     * Terima data traveler.
     * Catatan: penyimpanan Booking permanen & halaman checkout masih
     * menyusul di commit berikutnya (PRD section 39 Git Development
     * Strategy — grup Booking). Price breakdown sudah dihitung di sini
     * oleh BookingService (section 18: total dihitung backend, bukan
     * dipercayakan ke frontend).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'travelers' => ['required', 'array', 'min:1'],
            'travelers.*.full_name' => ['required', 'string', 'max:255'],
            'travelers.*.gender' => ['nullable', 'in:male,female'],
            'travelers.*.date_of_birth' => ['nullable', 'date'],
            'travelers.*.phone' => ['nullable', 'string', 'max:30'],
            'travelers.*.email' => ['nullable', 'email', 'max:255'],
        ]);

        $schedule = Schedule::with('trip')->findOrFail($validated['schedule_id']);
        $quantity = count($validated['travelers']);

        if (! $this->availability->checkAvailability($schedule, $quantity)) {
            return back()
                ->withInput()
                ->withErrors(['schedule_id' => 'Kuota tidak cukup. Sisa kursi: '.$schedule->fresh()->available_seats.'.']);
        }

        $breakdown = $this->bookingService->calculatePrice($schedule, $quantity);

        session([
            'pending_booking' => array_merge($validated, ['price_breakdown' => $breakdown]),
        ]);

        return redirect()
            ->route('checkout.show')
            ->with('status', "Data traveler tersimpan ({$quantity} orang). Silakan review sebelum konfirmasi.");
    }

    /**
     * Halaman checkout: review trip, schedule, travelers, dan price
     * breakdown sebelum konfirmasi booking (PRD section 18 Checkout
     * Requirements).
     */
    public function checkout(): View|RedirectResponse
    {
        $pending = session('pending_booking');

        if (! $pending) {
            return redirect()->route('trips.index')
                ->with('status', 'Tidak ada booking yang sedang diproses. Silakan pilih trip terlebih dahulu.');
        }

        $schedule = Schedule::with('trip.destination')->findOrFail($pending['schedule_id']);

        return view('bookings.checkout', [
            'schedule' => $schedule,
            'travelers' => $pending['travelers'],
            'breakdown' => $pending['price_breakdown'],
        ]);
    }

    /**
     * Konfirmasi booking: cek ulang availability, reservasi kursi, dan
     * simpan Booking + BookingTraveler secara atomic dalam satu
     * database transaction (PRD section 16: "Booking creation harus
     * menggunakan database transaction").
     *
     * Integrasi pembayaran Midtrans menyusul di fase Payment
     * (PRD section 39 — grup Payment).
     */
    public function confirm(): RedirectResponse
    {
        $pending = session('pending_booking');

        if (! $pending) {
            return redirect()->route('trips.index');
        }

        $schedule = Schedule::with('trip')->findOrFail($pending['schedule_id']);
        $quantity = count($pending['travelers']);
        $breakdown = $pending['price_breakdown'];

        if (! $this->availability->checkAvailability($schedule, $quantity)) {
            session()->forget('pending_booking');

            return redirect()->route('trips.show', $schedule->trip)
                ->withErrors(['schedule_id' => 'Kuota sudah tidak cukup lagi. Silakan pilih jadwal lain.']);
        }

        $booking = DB::transaction(function () use ($schedule, $quantity, $breakdown, $pending) {
            if (! $this->availability->reserve($schedule, $quantity)) {
                return null;
            }

            $booking = Booking::create([
                'booking_code' => 'TPG-'.strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'quantity' => $quantity,
                'price' => $breakdown['unit_price'],
                'service_fee' => $breakdown['service_fee'],
                'discount' => $breakdown['discount'],
                'total' => $breakdown['total'],
                'status' => 'pending',
            ]);

            foreach ($pending['travelers'] as $traveler) {
                $booking->travelers()->create($traveler);
            }

            $this->paymentService->createForBooking($booking);

            return $booking;
        });

        if (! $booking) {
            return redirect()->route('trips.show', $schedule->trip)
                ->withErrors(['schedule_id' => 'Kuota sudah tidak cukup lagi. Silakan pilih jadwal lain.']);
        }

        session()->forget('pending_booking');

        return redirect()
            ->route('bookings.confirmation', $booking)
            ->with('status', 'Booking berhasil dibuat! Kode booking: '.$booking->booking_code);
    }

    /**
     * Halaman konfirmasi sederhana setelah booking dibuat. Integrasi
     * pembayaran Midtrans & e-ticket menyusul di fase berikutnya.
     */
    public function confirmation(Booking $booking): View
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $booking->load('schedule.trip', 'travelers');

        return view('bookings.confirmation', ['booking' => $booking]);
    }
}
