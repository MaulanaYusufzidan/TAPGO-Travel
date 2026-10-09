<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Trip;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredDestinations = collect();
        $featuredTrips = collect();
        $dealHotels = collect();
        $popularCities = collect();
        $trendingHotels = collect();
        $reviews = collect();

        // Jaga homepage tetap tampil kalau DB lagi bermasalah (pola yang
        // sudah ada sebelumnya untuk Destination/Trip).
        try {
            $featuredDestinations = Destination::published()->featured()->take(6)->get();
            $featuredTrips = Trip::with(['destination', 'images'])->published()->featured()->take(6)->get();

            $dealHotels = Hotel::published()
                ->whereNotNull('discount_percentage')
                ->with('primaryImage')
                ->withMin('roomTypes', 'base_price')
                ->orderByDesc('discount_percentage')
                ->take(3)
                ->get();

            $popularCities = Hotel::published()
                ->selectRaw('city, province, count(*) as hotel_count')
                ->groupBy('city', 'province')
                ->orderByDesc('hotel_count')
                ->take(8)
                ->get()
                ->map(function ($row) {
                    $row->image = Hotel::published()->where('city', $row->city)->with('primaryImage')->first()?->primaryImage?->image_path;

                    return $row;
                });

            $trendingHotels = Hotel::published()
                ->with('primaryImage')
                ->withMin('roomTypes', 'base_price')
                ->withMax('roomTypes', 'base_price')
                ->withSum('roomTypes', 'quantity')
                ->orderByDesc('rating_avg')
                ->take(8)
                ->get();

            $reviews = Review::published()
                ->with(['user', 'hotel'])
                ->latest()
                ->take(5)
                ->get();
        } catch (\Illuminate\Database\QueryException) {
            // Empty-state fallback below handles this in the view.
        }

        return view('home', [
            'featuredDestinations' => $featuredDestinations,
            'featuredTrips' => $featuredTrips,
            'dealHotels' => $dealHotels,
            'popularCities' => $popularCities,
            'trendingHotels' => $trendingHotels,
            'reviews' => $reviews,
        ]);
    }
}
