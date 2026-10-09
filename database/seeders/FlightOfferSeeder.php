<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\FlightOffer;
use Illuminate\Database\Seeder;

class FlightOfferSeeder extends Seeder
{
    /**
     * Harus sinkron sama urutan $routes di FlightSeeder — dipakai buat
     * nentuin arah leg berangkat vs pulang secara eksplisit (bukan nebak
     * dari origin_code, biar gak ketuker kalau salah satu kota jadi
     * origin di satu rute dan destination di rute lain, ex: DPS).
     */
    protected array $routes = [
        ['origin' => 'CGK', 'dest' => 'DPS'],
        ['origin' => 'CGK', 'dest' => 'SUB'],
        ['origin' => 'CGK', 'dest' => 'JOG'],
        ['origin' => 'CGK', 'dest' => 'KNO'],
        ['origin' => 'CGK', 'dest' => 'UPG'],
        ['origin' => 'DPS', 'dest' => 'LOP'],
        ['origin' => 'CGK', 'dest' => 'SIN'],
        ['origin' => 'CGK', 'dest' => 'KUL'],
    ];

    protected array $classMultiplier = ['Economy' => 1.0, 'Business' => 2.2, 'First' => 3.5];

    public function run(): void
    {
        FlightOffer::query()->delete();

        foreach ($this->routes as $index => $route) {
            $departureFlight = Flight::where('origin_code', $route['origin'])
                ->where('destination_code', $route['dest'])
                ->orderBy('departure_at')
                ->first();

            if (! $departureFlight) {
                continue;
            }

            $returnFlight = Flight::where('origin_code', $route['dest'])
                ->where('destination_code', $route['origin'])
                ->where('departure_at', '>', $departureFlight->arrival_at)
                ->orderBy('departure_at')
                ->first();

            $basePrice = round(
                $departureFlight->duration_minutes * 12000 * ($this->classMultiplier[$departureFlight->travel_class] ?? 1),
                -3
            );

            $isRoundTrip = $returnFlight && $index % 4 !== 3; // sisain 1 dari 4 jadi one-way
            $discount = $index % 2 === 0 ? [10, 15, 20][$index % 3] : null;

            FlightOffer::create([
                'departure_flight_id' => $departureFlight->id,
                'return_flight_id' => $isRoundTrip ? $returnFlight->id : null,
                'base_price' => $isRoundTrip ? $basePrice * 2 : $basePrice,
                'discount_percentage' => $discount,
                'refundable' => in_array($departureFlight->travel_class, ['Business', 'First']),
                'seats_available' => fake()->numberBetween(2, 9),
                'status' => 'published',
            ]);
        }
    }
}
