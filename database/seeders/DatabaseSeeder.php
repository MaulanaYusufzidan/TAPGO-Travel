<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        $this->call([
            DestinationSeeder::class,
            CategorySeeder::class,
            TripSeeder::class,
        ]);

        $this->call([
            AmenitySeeder::class,
            HotelSeeder::class,
            HotelImageSeeder::class,
            RoomTypeSeeder::class,
            RoomImageSeeder::class,
            RoomInventorySeeder::class,
            HotelPolicySeeder::class,
            NearbyPlaceSeeder::class,
            ReviewSeeder::class,
        ]);

        $this->call([
            AirlineSeeder::class,
            FlightSeeder::class,
            FlightOfferSeeder::class,
        ]);
    }
}
