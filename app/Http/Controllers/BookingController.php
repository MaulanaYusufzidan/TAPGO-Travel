<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected AvailabilityService $availability,
        protected BookingService $bookingService,
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

        $total = 'Rp '.number_format($breakdown['total'], 0, ',', '.');

        return redirect()
            ->route('trips.show', $schedule->trip)
            ->with('status', "Data traveler tersimpan sementara ({$quantity} orang). Total: {$total} (subtotal Rp ".number_format($breakdown['subtotal'], 0, ',', '.').' + fee Rp '.number_format($breakdown['service_fee'], 0, ',', '.').'). Halaman checkout menyusul di commit berikutnya.');
    }
}
