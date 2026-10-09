<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\TripReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Ada 2 sistem review terpisah di database existing: Review (hotel,
     * skala 1-10) dan TripReview (trip, skala 1-5). Ditampilkan sebagai
     * 2 tab di 1 halaman, bukan dipaksa jadi 1 tabel gabungan.
     */
    public function index(Request $request): View
    {
        $hotelReviews = Review::with(['hotel', 'user'])
            ->when($request->filled('hotel_status'), fn ($q) => $q->where('status', $request->get('hotel_status')))
            ->latest()
            ->paginate(10, ['*'], 'hotel_page')
            ->withQueryString();

        $tripReviews = TripReview::with(['trip', 'user'])
            ->when($request->filled('trip_status'), fn ($q) => $q->where('status', $request->get('trip_status')))
            ->latest()
            ->paginate(10, ['*'], 'trip_page')
            ->withQueryString();

        return view('admin.reviews.index', [
            'hotelReviews' => $hotelReviews,
            'tripReviews' => $tripReviews,
            'filters' => $request->only(['hotel_status', 'trip_status']),
        ]);
    }

    public function updateHotelStatus(Request $request, Review $review): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:pending,published,rejected']]);

        $review->update(['status' => $request->get('status')]);

        $hotel = $review->hotel;
        $hotel->update([
            'rating_avg' => round($hotel->reviews()->published()->avg('rating'), 2) ?? 0,
            'reviews_count' => $hotel->reviews()->published()->count(),
        ]);

        return back()->with('status', 'Status review hotel diperbarui.');
    }

    public function updateTripStatus(Request $request, TripReview $tripReview): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:pending,published,rejected']]);

        $tripReview->update(['status' => $request->get('status')]);

        $trip = $tripReview->trip;
        $trip->update([
            'rating_avg' => round($trip->reviews()->published()->avg('rating'), 2) ?? 0,
            'reviews_count' => $trip->reviews()->published()->count(),
        ]);

        return back()->with('status', 'Status review trip diperbarui.');
    }

    public function destroyHotel(Review $review): RedirectResponse
    {
        $hotel = $review->hotel;
        $review->delete();

        $hotel->update([
            'rating_avg' => round($hotel->reviews()->published()->avg('rating'), 2) ?? 0,
            'reviews_count' => $hotel->reviews()->published()->count(),
        ]);

        return back()->with('status', 'Review hotel dihapus.');
    }

    public function destroyTrip(TripReview $tripReview): RedirectResponse
    {
        $trip = $tripReview->trip;
        $tripReview->delete();

        $trip->update([
            'rating_avg' => round($trip->reviews()->published()->avg('rating'), 2) ?? 0,
            'reviews_count' => $trip->reviews()->published()->count(),
        ]);

        return back()->with('status', 'Review trip dihapus.');
    }
}
