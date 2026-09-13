<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(protected AvailabilityService $availability)
    {
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
     * Catatan: penyimpanan Booking sesungguhnya, pengecekan
     * availability, kalkulasi harga, dan halaman checkout masih
     * menyusul di commit-commit berikutnya (PRD section 39 Git
     * Development Strategy — grup Booking).
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

        if (! $this->availability->checkAvailability($schedule, count($validated['travelers']))) {
            return back()
                ->withInput()
                ->withErrors(['schedule_id' => 'Kuota tidak cukup. Sisa kursi: '.$schedule->fresh()->available_seats.'.']);
        }

        session(['pending_booking' => $validated]);

        return redirect()
            ->route('trips.show', $schedule->trip)
            ->with('status', 'Data traveler tersimpan sementara ('.count($validated['travelers']).' orang). Pengecekan ketersediaan, kalkulasi harga, dan checkout menyusul di commit berikutnya.');
    }
}
