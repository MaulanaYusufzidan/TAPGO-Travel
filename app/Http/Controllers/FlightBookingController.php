<?php

namespace App\Http\Controllers;

use App\Models\FlightBooking;
use App\Models\FlightOffer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FlightBookingController extends Controller
{
    /**
     * Terima pilihan flight offer + jumlah penumpang dari halaman flight
     * detail. Cek kursi tersedia, hitung harga di backend, simpan
     * sementara ke session, lalu lempar ke checkout (mirror pola Hotel
     * booking — spec section 32-34).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'flight_offer_id' => ['required', 'exists:flight_offers,id'],
            'passengers' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        $offer = FlightOffer::with(['departureFlight.airline', 'returnFlight.airline'])
            ->findOrFail($validated['flight_offer_id']);

        if ($offer->seats_available < $validated['passengers']) {
            return back()->withErrors(['passengers' => 'Maaf, kursi tidak cukup untuk jumlah penumpang ini.']);
        }

        $breakdown = $this->calculatePrice($offer, (int) $validated['passengers']);

        session([
            'pending_flight_booking' => [
                'flight_offer_id' => $offer->id,
                'passengers' => (int) $validated['passengers'],
                'breakdown' => $breakdown,
            ],
        ]);

        return redirect()->route('flight-checkout.show');
    }

    /**
     * Halaman checkout: review penerbangan, isi Traveler Details per
     * penumpang (Name/Passport/DOB/Gender/Nationality, sesuai PDF
     * referensi), dan pilih metode pembayaran.
     */
    public function checkout(): View|RedirectResponse
    {
        $pending = session('pending_flight_booking');

        if (! $pending) {
            return redirect()->route('flights.index')
                ->with('status', 'Tidak ada pemesanan flight yang sedang diproses. Silakan pilih penerbangan terlebih dahulu.');
        }

        $offer = FlightOffer::with(['departureFlight.airline', 'returnFlight.airline'])
            ->findOrFail($pending['flight_offer_id']);

        return view('flight-bookings.checkout', [
            'offer' => $offer,
            'passengers' => $pending['passengers'],
            'breakdown' => $pending['breakdown'],
        ]);
    }

    /**
     * Konfirmasi booking: cek ulang kursi (row lock, cegah overbooking),
     * simpan FlightBooking + FlightBookingPassenger secara atomic.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $pending = session('pending_flight_booking');

        if (! $pending) {
            return redirect()->route('flights.index');
        }

        $passengerCount = $pending['passengers'];

        $validated = $request->validate([
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'payment_method' => ['required', 'in:bank_transfer,credit_card,e_wallet,qris'],
            'passengers' => ['required', 'array', 'size:'.$passengerCount],
            'passengers.*.first_name' => ['required', 'string', 'max:255'],
            'passengers.*.last_name' => ['required', 'string', 'max:255'],
            'passengers.*.passport_number' => ['required', 'string', 'max:50'],
            'passengers.*.passport_expiry' => ['required', 'date', 'after:today'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'passengers.*.gender' => ['required', 'in:male,female'],
            'passengers.*.nationality' => ['required', 'string', 'max:100'],
        ]);

        $offer = FlightOffer::findOrFail($pending['flight_offer_id']);
        $breakdown = $pending['breakdown'];

        $booking = DB::transaction(function () use ($offer, $passengerCount, $breakdown, $validated) {
            // Lock baris offer supaya dua booking bersamaan gak sama-sama
            // lolos ngelewatin kursi yang tersisa (spec section 46-47).
            $lockedOffer = FlightOffer::where('id', $offer->id)->lockForUpdate()->first();

            if ($lockedOffer->seats_available < $passengerCount) {
                return null;
            }

            $lockedOffer->decrement('seats_available', $passengerCount);

            $booking = FlightBooking::create([
                'booking_code' => 'TPGF-'.strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'flight_offer_id' => $offer->id,
                'passengers' => $passengerCount,
                'subtotal' => $breakdown['subtotal'],
                'tax' => $breakdown['tax'],
                'service_fee' => $breakdown['service_fee'],
                'discount' => $breakdown['discount'],
                'total' => $breakdown['total'],
                'status' => 'awaiting_payment',
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
            ]);

            $booking->passengerDetails()->createMany($validated['passengers']);

            $booking->payments()->create([
                'payment_code' => 'PAYF-'.strtoupper(Str::random(8)),
                'method' => $validated['payment_method'],
                'amount' => $breakdown['total'],
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);

            return $booking;
        });

        if (! $booking) {
            session()->forget('pending_flight_booking');

            return redirect()->route('flights.show', $offer)
                ->withErrors(['passengers' => 'Kursi sudah tidak cukup tersedia lagi. Silakan pilih penerbangan lain.']);
        }

        session()->forget('pending_flight_booking');

        return redirect()
            ->route('flight-bookings.payment', $booking)
            ->with('status', 'Booking flight berhasil dibuat! Kode booking: '.$booking->booking_code);
    }

    /**
     * Halaman pembayaran mock (spec section 37) — belum terhubung ke
     * payment gateway asli, cuma simulasi Success/Failed buat kebutuhan
     * demo/tugas.
     */
    public function payment(FlightBooking $flightBooking): View
    {
        abort_unless($flightBooking->user_id === Auth::id(), 403);

        $flightBooking->load('payments');
        $payment = $flightBooking->payments()->latest()->first();

        return view('flight-bookings.payment', ['booking' => $flightBooking, 'payment' => $payment]);
    }

    /**
     * Tombol "Simulate Successful/Failed Payment" — jangan diekspos di
     * production (spec section 37).
     */
    public function simulatePayment(Request $request, FlightBooking $flightBooking): RedirectResponse
    {
        abort_unless($flightBooking->user_id === Auth::id(), 403);
        abort_if(app()->environment('production'), 404);

        $request->validate(['result' => ['required', 'in:success,failed']]);

        $payment = $flightBooking->payments()->latest()->first();

        if ($request->input('result') === 'success') {
            $payment->update(['status' => 'paid', 'paid_at' => now()]);
            $flightBooking->update(['status' => 'confirmed']);
        } else {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('flight-bookings.confirmation', $flightBooking);
    }

    public function confirmation(FlightBooking $flightBooking): View
    {
        abort_unless($flightBooking->user_id === Auth::id(), 403);

        $flightBooking->load(['flightOffer.departureFlight.airline', 'flightOffer.returnFlight.airline', 'passengerDetails', 'payments']);

        return view('flight-bookings.confirmation', ['booking' => $flightBooking]);
    }

    /**
     * PRD section 34: harga selalu dihitung ulang di backend, bukan dari
     * input user.
     */
    protected function calculatePrice(FlightOffer $offer, int $passengers): array
    {
        $subtotal = $offer->final_price * $passengers;

        $taxPercentage = (float) config('booking.tax_percentage', 11);
        $feePercentage = (float) config('booking.service_fee_percentage', 2);

        $tax = round($subtotal * ($taxPercentage / 100), 2);
        $serviceFee = round($subtotal * ($feePercentage / 100), 2);
        $total = $subtotal + $tax + $serviceFee;

        return [
            'passengers' => $passengers,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'service_fee' => $serviceFee,
            'discount' => 0,
            'total' => $total,
        ];
    }
}
