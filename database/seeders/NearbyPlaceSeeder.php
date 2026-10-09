<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class NearbyPlaceSeeder extends Seeder
{
    /**
     * Nearby places digrupkan per kota (bukan per hotel), supaya semua hotel
     * di kota yang sama tetap punya referensi lokasi yang masuk akal
     * (spec section 12).
     */
    protected array $byCity = [
        'Badung' => [
            ['name' => 'Ngurah Rai International Airport', 'category' => 'Airport', 'distance' => 12.5, 'unit' => 'km'],
            ['name' => 'Pantai Kuta', 'category' => 'Attraction', 'distance' => 2.0, 'unit' => 'km'],
            ['name' => 'Beachwalk Shopping Center', 'category' => 'Shopping', 'distance' => 1.5, 'unit' => 'km'],
            ['name' => 'Warung Bebek Bengil', 'category' => 'Restaurant', 'distance' => 0.8, 'unit' => 'km'],
        ],
        'Gianyar' => [
            ['name' => 'Pasar Seni Ubud', 'category' => 'Shopping', 'distance' => 1.2, 'unit' => 'km'],
            ['name' => 'Sacred Monkey Forest Sanctuary', 'category' => 'Attraction', 'distance' => 2.5, 'unit' => 'km'],
            ['name' => 'Ngurah Rai International Airport', 'category' => 'Airport', 'distance' => 35.0, 'unit' => 'km'],
        ],
        'Jakarta Selatan' => [
            ['name' => 'Soekarno-Hatta International Airport', 'category' => 'Airport', 'distance' => 28.0, 'unit' => 'km'],
            ['name' => 'Senayan City Mall', 'category' => 'Shopping', 'distance' => 2.0, 'unit' => 'km'],
            ['name' => 'MRT Jakarta - Blok M', 'category' => 'Transportation', 'distance' => 900, 'unit' => 'm'],
            ['name' => 'Kemang Food Street', 'category' => 'Cafe', 'distance' => 1.1, 'unit' => 'km'],
        ],
        'Bandung' => [
            ['name' => 'Bandung Husein Sastranegara Airport', 'category' => 'Airport', 'distance' => 8.0, 'unit' => 'km'],
            ['name' => 'Kawah Putih', 'category' => 'Attraction', 'distance' => 24.0, 'unit' => 'km'],
            ['name' => 'Factory Outlet Dago', 'category' => 'Shopping', 'distance' => 1.0, 'unit' => 'km'],
        ],
        'Yogyakarta' => [
            ['name' => 'Malioboro Street', 'category' => 'Shopping', 'distance' => 200, 'unit' => 'm'],
            ['name' => 'Keraton Yogyakarta', 'category' => 'Attraction', 'distance' => 1.5, 'unit' => 'km'],
            ['name' => 'Yogyakarta International Airport', 'category' => 'Airport', 'distance' => 42.0, 'unit' => 'km'],
        ],
        'Lombok Utara' => [
            ['name' => 'Pantai Senggigi', 'category' => 'Attraction', 'distance' => 500, 'unit' => 'm'],
            ['name' => 'Zainuddin Abdul Madjid International Airport', 'category' => 'Airport', 'distance' => 45.0, 'unit' => 'km'],
        ],
        'Surabaya' => [
            ['name' => 'Juanda International Airport', 'category' => 'Airport', 'distance' => 20.0, 'unit' => 'km'],
            ['name' => 'Tunjungan Plaza', 'category' => 'Shopping', 'distance' => 1.0, 'unit' => 'km'],
            ['name' => 'Stasiun Gubeng', 'category' => 'Transportation', 'distance' => 1.8, 'unit' => 'km'],
        ],
        'Bogor' => [
            ['name' => 'Kebun Raya Bogor', 'category' => 'Attraction', 'distance' => 6.0, 'unit' => 'km'],
            ['name' => 'Taman Safari Indonesia', 'category' => 'Attraction', 'distance' => 3.0, 'unit' => 'km'],
            ['name' => 'Rest Area Puncak', 'category' => 'Restaurant', 'distance' => 1.0, 'unit' => 'km'],
        ],
    ];

    public function run(): void
    {
        Hotel::all()->each(function (Hotel $hotel) {
            $hotel->nearbyPlaces()->delete();

            $places = $this->byCity[$hotel->city] ?? [];
            foreach ($places as $place) {
                $hotel->nearbyPlaces()->create($place);
            }
        });
    }
}
