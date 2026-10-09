<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Flight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FlightSeeder extends Seeder
{
    /**
     * Rute populer (domestik + 2 internasional), dipakai FlightOfferSeeder
     * buat nyusun paket one-way/round-trip (spec: mirror pola Hotel).
     */
    protected array $routes = [
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'DPS', 'destCity' => 'Bali', 'duration' => 110],
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'SUB', 'destCity' => 'Surabaya', 'duration' => 80],
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'JOG', 'destCity' => 'Yogyakarta', 'duration' => 70],
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'KNO', 'destCity' => 'Medan', 'duration' => 140],
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'UPG', 'destCity' => 'Makassar', 'duration' => 130],
        ['origin' => 'DPS', 'originCity' => 'Bali', 'dest' => 'LOP', 'destCity' => 'Lombok', 'duration' => 30],
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'SIN', 'destCity' => 'Singapore', 'duration' => 105],
        ['origin' => 'CGK', 'originCity' => 'Jakarta', 'dest' => 'KUL', 'destCity' => 'Kuala Lumpur', 'duration' => 120],
    ];

    protected array $classes = ['Economy', 'Economy', 'Economy', 'Business', 'First'];

    public function run(): void
    {
        $airlineIds = Airline::pluck('id')->all();
        $departTimes = ['06:00', '08:30', '11:00', '14:10', '18:40'];

        foreach ($this->routes as $i => $route) {
            $airlineId = $airlineIds[$i % count($airlineIds)];
            $class = $this->classes[$i % count($this->classes)];
            $departDate = Carbon::today()->addDays(5 + $i)->setTimeFromTimeString($departTimes[$i % count($departTimes)]);

            // Leg berangkat
            Flight::updateOrCreate([
                'origin_code' => $route['origin'],
                'destination_code' => $route['dest'],
                'departure_at' => $departDate,
            ], [
                'airline_id' => $airlineId,
                'flight_number' => strtoupper(substr($route['dest'], 0, 2)).(100 + $i),
                'origin_city' => $route['originCity'],
                'destination_city' => $route['destCity'],
                'arrival_at' => $departDate->copy()->addMinutes($route['duration']),
                'duration_minutes' => $route['duration'],
                'stops' => $i % 4 === 0 ? 1 : 0,
                'travel_class' => $class,
                'wifi' => in_array($class, ['Business', 'First']),
                'meal' => $class !== 'Economy' || $route['duration'] > 100,
                'baggage_kg' => $class === 'Economy' ? 20 : 30,
            ]);

            // Leg pulang (2 hari setelah berangkat), rute dibalik
            $returnDate = $departDate->copy()->addDays(2)->setTimeFromTimeString($departTimes[($i + 2) % count($departTimes)]);

            Flight::updateOrCreate([
                'origin_code' => $route['dest'],
                'destination_code' => $route['origin'],
                'departure_at' => $returnDate,
            ], [
                'airline_id' => $airlineId,
                'flight_number' => strtoupper(substr($route['origin'], 0, 2)).(200 + $i),
                'origin_city' => $route['destCity'],
                'destination_city' => $route['originCity'],
                'arrival_at' => $returnDate->copy()->addMinutes($route['duration']),
                'duration_minutes' => $route['duration'],
                'stops' => 0,
                'travel_class' => $this->classes[($i + 1) % count($this->classes)],
                'wifi' => true,
                'meal' => $route['duration'] > 100,
                'baggage_kg' => 20,
            ]);
        }
    }
}
