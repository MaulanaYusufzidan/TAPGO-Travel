<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'recommended');

        $trips = Trip::query()
            ->with(['destination', 'category'])
            ->published()
            ->search($request->get('q'))
            ->when($request->filled('destination'), function ($q) use ($request) {
                $q->whereHas('destination', fn ($d) => $d->where('slug', $request->get('destination')));
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $request->get('category')));
            })
            ->when($request->filled('price_min'), fn ($q) => $q->where('base_price', '>=', $request->get('price_min')))
            ->when($request->filled('price_max'), fn ($q) => $q->where('base_price', '<=', $request->get('price_max')))
            ->when($sort === 'price_asc', fn ($q) => $q->orderBy('base_price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('base_price', 'desc'))
            ->when($sort === 'rating', fn ($q) => $q->orderByDesc('rating_avg'))
            ->when($sort === 'popularity', fn ($q) => $q->orderByDesc('reviews_count'))
            ->when($sort === 'recommended', fn ($q) => $q->orderByDesc('is_featured')->orderByDesc('rating_avg'))
            ->paginate(9)
            ->withQueryString();

        return view('trips.index', [
            'trips' => $trips,
            'destinations' => Destination::published()->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['q', 'destination', 'category', 'price_min', 'price_max', 'sort']),
        ]);
    }

    public function show(Trip $trip): View
    {
        abort_unless($trip->status === 'published', 404);

        $trip->load(['destination', 'category', 'images', 'itineraries', 'inclusions', 'exclusions']);

        $schedules = $trip->schedules()
            ->available()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();

        $related = Trip::query()
            ->with(['destination', 'images'])
            ->published()
            ->where('id', '!=', $trip->id)
            ->where('destination_id', $trip->destination_id)
            ->limit(3)
            ->get();

        return view('trips.show', [
            'trip' => $trip,
            'schedules' => $schedules,
            'related' => $related,
        ]);
    }
}
