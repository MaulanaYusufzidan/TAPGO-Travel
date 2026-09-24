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
        ['name' => 'Swimming Pool', 'category' => 'Wellness', 'icon' => 'pool', 'image_path' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Parking', 'category' => 'General', 'icon' => 'parking'],
        ['name' => 'Air Conditioning', 'category' => 'Room', 'icon' => 'snowflake'],
        ['name' => 'Restaurant', 'category' => 'Food & Drink', 'icon' => 'utensils', 'image_path' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Fitness Center', 'category' => 'Wellness', 'icon' => 'dumbbell', 'image_path' => 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Spa', 'category' => 'Wellness', 'icon' => 'spa'],
        ['name' => 'Airport Shuttle', 'category' => 'General', 'icon' => 'shuttle'],
        ['name' => '24 Hour Front Desk', 'category' => 'General', 'icon' => 'clock'],
        ['name' => 'Breakfast Included', 'category' => 'Food & Drink', 'icon' => 'coffee'],
        ['name' => 'Non-Smoking Rooms', 'category' => 'Room', 'icon' => 'smoke-off'],
        ['name' => 'Room Service', 'category' => 'Room', 'icon' => 'bell'],
        ['name' => 'Bar', 'category' => 'Food & Drink', 'icon' => 'glass', 'image_path' => 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Laundry Service', 'category' => 'General', 'icon' => 'washing-machine'],
        ['name' => 'Elevator', 'category' => 'General', 'icon' => 'elevator'],
        ['name' => 'Family Rooms', 'category' => 'Room', 'icon' => 'users'],
        ['name' => 'Pet Friendly', 'category' => 'General', 'icon' => 'paw'],
        ['name' => 'Meeting Room', 'category' => 'Business', 'icon' => 'briefcase', 'image_path' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=400&q=80'],
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
