<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin view untuk booking Hotel. Data dari tabel `hotel_bookings`
 * (BUKAN `bookings` milik Trip). Read-only: index + show.
 */
class HotelBookingController extends Controller
{
    public const STATUSES = ['pending', 'awaiting_payment', 'paid', 'confirmed', 'completed', 'cancelled', 'expired'];

    public function index(Request $request): View
    {
        $bookings = HotelBooking::with(['user', 'hotel'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($qq) use ($term) {
                    $qq->where('booking_code', 'like', "%{$term}%")
                        ->orWhere('guest_name', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.hotel-bookings.index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'q']),
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(HotelBooking $hotelBooking): View
    {
        $hotelBooking->load(['user', 'hotel', 'bookingRooms.roomType', 'bookingRooms.ratePlan', 'payments']);

        return view('admin.hotel-bookings.show', ['booking' => $hotelBooking]);
    }
}
