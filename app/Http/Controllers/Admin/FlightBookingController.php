<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin view untuk booking Flight. Data dari tabel `flight_bookings`
 * (BUKAN `bookings` milik Trip). Read-only: index + show.
 */
class FlightBookingController extends Controller
{
    public const STATUSES = ['pending', 'awaiting_payment', 'paid', 'confirmed', 'completed', 'cancelled', 'expired'];

    public function index(Request $request): View
    {
        $bookings = FlightBooking::with(['user', 'flightOffer.departureFlight.airline'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($qq) use ($term) {
                    $qq->where('booking_code', 'like', "%{$term}%")
                        ->orWhere('contact_email', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.flight-bookings.index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'q']),
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(FlightBooking $flightBooking): View
    {
        $flightBooking->load([
            'user',
            'flightOffer.departureFlight.airline',
            'flightOffer.returnFlight.airline',
            'passengerDetails',
            'payments',
        ]);

        return view('admin.flight-bookings.show', ['booking' => $flightBooking]);
    }
}
