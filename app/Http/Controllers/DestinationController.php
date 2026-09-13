<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'recommended');

        $destinations = Destination::query()
            ->published()
            ->search($request->get('q'))
            ->when($request->filled('location'), fn ($q) => $q->where('location', $request->get('location')))
            ->when($sort === 'name_asc', fn ($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn ($q) => $q->orderBy('name', 'desc'))
            ->when($sort === 'newest', fn ($q) => $q->latest())
            ->when($sort === 'recommended', fn ($q) => $q->orderByDesc('is_featured')->orderBy('name'))
            ->paginate(9)
            ->withQueryString();

        $locations = Destination::published()
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('destinations.index', [
            'destinations' => $destinations,
            'locations' => $locations,
            'filters' => $request->only(['q', 'location', 'sort']),
        ]);
    }
}
