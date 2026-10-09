<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'recommended');

        $hotels = Hotel::query()
            ->published()
            ->search($request->get('destination'))
            ->minRating($request->get('min_rating'))
            ->hotelType($request->get('hotel_type'))
            ->starRatings($request->input('star_ratings', []))
            ->bedTypes($request->input('bed_types', []))
            ->withAmenityIds($request->input('amenities', []))
            ->breakfastIncluded($request->boolean('breakfast'))
            ->freeCancellation($request->boolean('free_cancellation'))
            ->priceBetween($request->integer('min_price') ?: null, $request->integer('max_price') ?: null)
            ->withMin('roomTypes', 'base_price')
            ->with(['primaryImage', 'amenities'])
            ->when($sort === 'price_low', fn ($q) => $q->orderBy('room_types_min_base_price'))
            ->when($sort === 'price_high', fn ($q) => $q->orderByDesc('room_types_min_base_price'))
            ->when($sort === 'rating', fn ($q) => $q->orderByDesc('rating_avg'))
            ->when($sort === 'most_reviewed', fn ($q) => $q->orderByDesc('reviews_count'))
            ->when($sort === 'recommended', fn ($q) => $q->orderByDesc('is_featured')->orderByDesc('rating_avg'))
            ->paginate(9)
            ->withQueryString();

        return view('pages.hotels', [
            'hotels' => $hotels,
            'amenities' => Amenity::orderBy('name')->get(),
            'hotelTypes' => Hotel::published()->whereNotNull('hotel_type')->distinct()->orderBy('hotel_type')->pluck('hotel_type'),
            'bedTypes' => RoomType::whereHas('hotel', fn ($q) => $q->published())->whereNotNull('bed_type')->distinct()->orderBy('bed_type')->pluck('bed_type'),
            'filters' => $request->only([
                'destination', 'check_in', 'check_out', 'guests',
                'min_price', 'max_price', 'min_rating', 'hotel_type',
                'star_ratings', 'bed_types',
                'amenities', 'breakfast', 'free_cancellation', 'sort',
            ]),
        ]);
    }

    public function show(Request $request, Hotel $hotel): View
    {
        abort_unless($hotel->status === 'published', 404);

        $hotel->load([
            'images' => fn ($q) => $q->orderBy('sort_order'),
            'amenities',
            'policy',
            'nearbyPlaces',
            'roomTypes' => fn ($q) => $q->where('status', 'published')->orderBy('base_price'),
            'roomTypes.images' => fn ($q) => $q->orderBy('sort_order'),
            'roomTypes.amenities',
            'roomTypes.ratePlans',
            'reviews' => fn ($q) => $q->published()->with('user')->latest()->limit(10),
        ]);

        $related = Hotel::query()
            ->published()
            ->where('id', '!=', $hotel->id)
            ->where('city', $hotel->city)
            ->with('primaryImage')
            ->withMin('roomTypes', 'base_price')
            ->limit(3)
            ->get();

        if ($related->isEmpty()) {
            $related = Hotel::query()
                ->published()
                ->where('id', '!=', $hotel->id)
                ->with('primaryImage')
                ->withMin('roomTypes', 'base_price')
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }

        // Breakdown dihitung dari SEMUA review published, bukan cuma 10 yang
        // di-load di atas untuk ditampilkan (spec section 26 - Guest Reviews).
        $reviewBreakdown = [
            'Cleanliness' => round((float) $hotel->reviews()->published()->avg('cleanliness_rating'), 1),
            'Location' => round((float) $hotel->reviews()->published()->avg('location_rating'), 1),
            'Service' => round((float) $hotel->reviews()->published()->avg('service_rating'), 1),
            'Value' => round((float) $hotel->reviews()->published()->avg('value_rating'), 1),
        ];

        $originalFromPrice = $hotel->roomTypes->min('base_price');

        return view('pages.hotel-detail', [
            'hotel' => $hotel,
            'related' => $related,
            'reviewBreakdown' => $reviewBreakdown,
            'fromPrice' => $originalFromPrice ? $hotel->priceAfterDiscount($originalFromPrice) : null,
            'originalFromPrice' => $originalFromPrice,
            'stay' => [
                'check_in' => $request->get('check_in', now()->addDay()->toDateString()),
                'check_out' => $request->get('check_out', now()->addDays(2)->toDateString()),
                'guests' => (int) $request->get('guests', 2),
            ],
        ]);
    }
}
