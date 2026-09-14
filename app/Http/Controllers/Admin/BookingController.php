<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(protected AvailabilityService $availability)
    {
    }

    /**
     * PRD section 26 Admin Management — Booking: View / Update status / Cancel.
     */
    public function index(Request $request): View
    {
        $bookings = Booking::with(['user', 'schedule.trip'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($qq) use ($term) {
                    $qq->where('booking_code', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'q']),
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'schedule.trip.destination', 'travelers', 'payments']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,expired'],
        ]);

        $wasActive = in_array($booking->status, ['pending', 'confirmed']);
        $becomingInactive = in_array($validated['status'], ['cancelled', 'expired']);

        // Lepas kembali kursi yang direservasi kalau booking dibatalkan
        // (PRD section 16: konsistensi availability).
        if ($wasActive && $becomingInactive) {
            $this->availability->release($booking->schedule, $booking->quantity);
        }

        $booking->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('status', 'Status booking berhasil diperbarui menjadi '.$validated['status'].'.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        if (in_array($booking->status, ['cancelled', 'expired', 'completed'])) {
            return back()->withErrors(['status' => 'Booking ini tidak bisa dibatalkan lagi.']);
        }

        $this->availability->release($booking->schedule, $booking->quantity);
        $booking->update(['status' => 'cancelled']);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('status', 'Booking berhasil dibatalkan, kursi dikembalikan ke schedule.');
    }
}
