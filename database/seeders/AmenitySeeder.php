<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Master fasilitas hotel & kamar (spec section 6).
     * Dipakai bersama oleh Hotel::amenities() dan RoomType::amenities().
     */
    protected array $amenities = [
        ['name' => 'Free WiFi', 'category' => 'Connectivity', 'icon' => 'wifi'],
        ['name' => 'Swimming Pool', 'category' => 'Wellness', 'icon' => 'pool'],
        ['name' => 'Parking', 'category' => 'General', 'icon' => 'parking'],
        ['name' => 'Air Conditioning', 'category' => 'Room', 'icon' => 'snowflake'],
        ['name' => 'Restaurant', 'category' => 'Food & Drink', 'icon' => 'utensils'],
        ['name' => 'Fitness Center', 'category' => 'Wellness', 'icon' => 'dumbbell'],
        ['name' => 'Spa', 'category' => 'Wellness', 'icon' => 'spa'],
        ['name' => 'Airport Shuttle', 'category' => 'General', 'icon' => 'shuttle'],
        ['name' => '24 Hour Front Desk', 'category' => 'General', 'icon' => 'clock'],
        ['name' => 'Breakfast Included', 'category' => 'Food & Drink', 'icon' => 'coffee'],
        ['name' => 'Non-Smoking Rooms', 'category' => 'Room', 'icon' => 'smoke-off'],
        ['name' => 'Room Service', 'category' => 'Room', 'icon' => 'bell'],
        ['name' => 'Bar', 'category' => 'Food & Drink', 'icon' => 'glass'],
        ['name' => 'Laundry Service', 'category' => 'General', 'icon' => 'washing-machine'],
        ['name' => 'Elevator', 'category' => 'General', 'icon' => 'elevator'],
        ['name' => 'Family Rooms', 'category' => 'Room', 'icon' => 'users'],
        ['name' => 'Pet Friendly', 'category' => 'General', 'icon' => 'paw'],
        ['name' => 'Meeting Room', 'category' => 'Business', 'icon' => 'briefcase'],
        ['name' => 'TV', 'category' => 'Room', 'icon' => 'tv'],
        ['name' => 'Mini Refrigerator', 'category' => 'Room', 'icon' => 'fridge'],
        ['name' => 'Private Bathroom', 'category' => 'Room', 'icon' => 'shower'],
        ['name' => 'Beachfront', 'category' => 'Location', 'icon' => 'umbrella-beach'],
    ];

    public function run(): void
    {
        foreach ($this->amenities as $amenity) {
            Amenity::updateOrCreate(
                ['slug' => str($amenity['name'])->slug()],
                array_merge($amenity, ['slug' => str($amenity['name'])->slug()])
            );
        }
    }
}
