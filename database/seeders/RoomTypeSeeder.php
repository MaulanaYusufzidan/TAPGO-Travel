<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Template tipe kamar generik (spec section 7). Setiap hotel dapat subset
     * dari template ini, jumlahnya mengikuti star_rating (makin tinggi bintang,
     * makin banyak pilihan tipe kamar).
     */
    protected array $templates = [
        [
            'name' => 'Standard Room',
            'max_guests' => 2,
            'bed_type' => '1 Queen Bed',
            'size_sqm' => 24,
            'multiplier' => 1.0,
            'breakfast_included' => false,
            'free_cancellation' => false,
            'amenities' => ['Free WiFi', 'Air Conditioning', 'TV', 'Private Bathroom'],
        ],
        [
            'name' => 'Superior Room',
            'max_guests' => 2,
            'bed_type' => '1 King Bed',
            'size_sqm' => 28,
            'multiplier' => 1.3,
            'breakfast_included' => false,
            'free_cancellation' => false,
            'amenities' => ['Free WiFi', 'Air Conditioning', 'TV', 'Private Bathroom', 'Mini Refrigerator'],
        ],
        [
            'name' => 'Deluxe Room',
            'max_guests' => 2,
            'bed_type' => '1 King Bed',
            'size_sqm' => 32,
            'multiplier' => 1.6,
            'breakfast_included' => true,
            'free_cancellation' => true,
            'amenities' => ['Free WiFi', 'Air Conditioning', 'TV', 'Private Bathroom', 'Mini Refrigerator', 'Non-Smoking Rooms'],
        ],
        [
            'name' => 'Family Room',
            'max_guests' => 4,
            'bed_type' => '2 Queen Beds',
            'size_sqm' => 40,
            'multiplier' => 2.0,
            'breakfast_included' => true,
            'free_cancellation' => false,
            'amenities' => ['Free WiFi', 'Air Conditioning', 'TV', 'Private Bathroom', 'Mini Refrigerator'],
        ],
        [
            'name' => 'Suite',
            'max_guests' => 3,
            'bed_type' => '1 King Bed + 1 Sofa Bed',
            'size_sqm' => 55,
            'multiplier' => 2.6,
            'breakfast_included' => true,
            'free_cancellation' => true,
            'amenities' => ['Free WiFi', 'Air Conditioning', 'TV', 'Private Bathroom', 'Mini Refrigerator', 'Room Service', 'Non-Smoking Rooms'],
        ],
    ];

    public function run(): void
    {
        Hotel::all()->each(function (Hotel $hotel) {
            $basePrice = $hotel->star_rating * 350000;

            // Makin tinggi bintang, makin banyak pilihan tipe kamar (2-5).
            $count = min(2 + max(0, $hotel->star_rating - 3), count($this->templates));
            $selected = collect($this->templates)->take($count);

            $hotel->roomTypes()->delete();

            foreach ($selected as $template) {
                $amenityNames = $template['amenities'];

                $roomType = $hotel->roomTypes()->create([
                    'name' => $template['name'],
                    'slug' => str($template['name'])->slug(),
                    'description' => "{$template['name']} di {$hotel->name}, dilengkapi {$template['bed_type']} dan fasilitas lengkap untuk {$template['max_guests']} tamu.",
                    'max_guests' => $template['max_guests'],
                    'bed_type' => $template['bed_type'],
                    'size_sqm' => $template['size_sqm'],
                    'quantity' => fake()->numberBetween(3, 10),
                    'base_price' => round($basePrice * $template['multiplier'], -3),
                    'breakfast_included' => $template['breakfast_included'],
                    'free_cancellation' => $template['free_cancellation'],
                    'status' => 'published',
                ]);

                $amenityIds = Amenity::whereIn('name', $amenityNames)->pluck('id');
                $roomType->amenities()->sync($amenityIds);

                // 2 pilihan harga per kamar, pola "Your Choice" di PDF
                // referensi GeoTrip: Room Only (murah, non-refundable) vs
                // Breakfast Included (lebih mahal, refundable).
                $roomType->ratePlans()->delete();
                $breakfastAddon = round($roomType->base_price * 0.12, -3);

                $roomType->ratePlans()->createMany([
                    [
                        'name' => 'Room Only',
                        'price_addon' => 0,
                        'breakfast_included' => false,
                        'free_cancellation' => false,
                        'refundable' => false,
                        'sort_order' => 0,
                    ],
                    [
                        'name' => 'Breakfast Included',
                        'price_addon' => $breakfastAddon,
                        'breakfast_included' => true,
                        'free_cancellation' => true,
                        'refundable' => true,
                        'sort_order' => 1,
                    ],
                ]);
            }
        });
    }
}
