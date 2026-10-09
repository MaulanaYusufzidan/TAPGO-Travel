<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\FlightOffer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'recommended');

        $offers = FlightOffer::query()
            ->published()
            ->route($request->get('from'), $request->get('to'))
            ->departureDate($request->get('departure_date'))
            ->stops('departureFlight', $request->input('onward_stops', []))
            ->stops('returnFlight', $request->input('return_stops', []))
            ->timeBucket('departureFlight', $request->get('departure_time'))
            ->timeBucket('returnFlight', $request->get('return_time'))
            ->priceBetween($request->integer('min_price') ?: null, $request->integer('max_price') ?: null)
            ->airlineIds($request->input('airlines', []))
            ->facility('wifi', $request->boolean('wifi'))
            ->facility('meal', $request->boolean('meal'))
            ->with(['departureFlight.airline', 'returnFlight.airline'])
            ->when($sort === 'price_low', fn ($q) => $q->orderBy('base_price'))
            ->when($sort === 'most_reviewed', fn ($q) => $q->orderByDesc('seats_available'))
            ->when($sort === 'recommended', fn ($q) => $q->orderByDesc('refundable')->orderBy('base_price'))
            ->paginate(9)
            ->withQueryString();

        return view('pages.flights', [
            'offers' => $offers,
            'airlines' => Airline::orderBy('name')->get(),
            'filters' => $request->only([
                'from', 'to', 'departure_date',
                'onward_stops', 'return_stops', 'departure_time', 'return_time',
                'min_price', 'max_price', 'airlines', 'wifi', 'meal', 'sort',
            ]),
        ]);
    }

    public function show(FlightOffer $flightOffer): View
    {
        abort_unless($flightOffer->status === 'published', 404);

        $flightOffer->load(['departureFlight.airline', 'returnFlight.airline']);

        $related = FlightOffer::query()
            ->published()
            ->where('id', '!=', $flightOffer->id)
            ->with('departureFlight')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('pages.flight-detail', [
            'offer' => $flightOffer,
            'related' => $related,
        ]);
    }
}
