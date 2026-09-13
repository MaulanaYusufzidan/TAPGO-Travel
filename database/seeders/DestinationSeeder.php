<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Curated Indonesian destinations untuk demo/homepage.
     * Ref: PRD section 11 (Destination Requirements), section 32 (Brand Identity)
     */
    protected array $destinations = [
        [
            'name' => 'Bali',
            'location' => 'Bali',
            'description' => 'Pulau Dewata dengan pantai, sawah berundak, dan budaya yang kaya.',
            'things_to_do' => ['Surfing di Uluwatu', 'Sunset di Tanah Lot', 'Menjelajah sawah Tegalalang', 'Wisata kuliner Seminyak'],
            'travel_information' => ['best_time' => 'April - Oktober', 'currency' => 'IDR', 'language' => 'Bahasa Indonesia'],
            'is_featured' => true,
            'images' => ['destinations/bali-1.jpg', 'destinations/bali-2.jpg', 'destinations/bali-3.jpg'],
        ],
        [
            'name' => 'Yogyakarta',
            'location' => 'Yogyakarta',
            'description' => 'Kota budaya dengan candi bersejarah dan kuliner legendaris.',
            'things_to_do' => ['Sunrise di Candi Borobudur', 'Menjelajah Malioboro', 'Wisata Candi Prambanan', 'Kuliner gudeg'],
            'travel_information' => ['best_time' => 'Mei - September', 'currency' => 'IDR', 'language' => 'Bahasa Indonesia'],
            'is_featured' => true,
            'images' => ['destinations/yogyakarta-1.jpg', 'destinations/yogyakarta-2.jpg'],
        ],
        [
            'name' => 'Raja Ampat',
            'location' => 'Papua Barat',
            'description' => 'Surga bawah laut dengan keanekaragaman hayati terbaik di dunia.',
            'things_to_do' => ['Diving & snorkeling', 'Island hopping', 'Melihat burung Cendrawasih', 'Fotografi bawah laut'],
            'travel_information' => ['best_time' => 'Oktober - April', 'currency' => 'IDR', 'language' => 'Bahasa Indonesia'],
            'is_featured' => true,
            'images' => ['destinations/raja-ampat-1.jpg', 'destinations/raja-ampat-2.jpg'],
        ],
        [
            'name' => 'Bromo Tengger Semeru',
            'location' => 'Jawa Timur',
            'description' => 'Kawasan gunung berapi ikonik dengan lautan pasir dan sunrise spektakuler.',
            'things_to_do' => ['Sunrise di Penanjakan', 'Jeep tour lautan pasir', 'Trekking ke kawah Bromo'],
            'travel_information' => ['best_time' => 'April - Oktober', 'currency' => 'IDR', 'language' => 'Bahasa Indonesia'],
            'is_featured' => false,
            'images' => ['destinations/bromo-1.jpg', 'destinations/bromo-2.jpg'],
        ],
        [
            'name' => 'Labuan Bajo',
            'location' => 'Nusa Tenggara Timur',
            'description' => 'Gerbang menuju Taman Nasional Komodo dan pulau-pulau eksotis.',
            'things_to_do' => ['Melihat komodo di Pulau Komodo', 'Snorkeling di Pink Beach', 'Island hopping', 'Sunset sailing'],
            'travel_information' => ['best_time' => 'April - Desember', 'currency' => 'IDR', 'language' => 'Bahasa Indonesia'],
            'is_featured' => true,
            'images' => ['destinations/labuan-bajo-1.jpg', 'destinations/labuan-bajo-2.jpg'],
        ],
        [
            'name' => 'Bandung',
            'location' => 'Jawa Barat',
            'description' => 'Kota kembang dengan udara sejuk, wisata alam, dan factory outlet.',
            'things_to_do' => ['Wisata Kawah Putih', 'Belanja factory outlet', 'Kuliner khas Bandung', 'Wisata Lembang'],
            'travel_information' => ['best_time' => 'Sepanjang tahun', 'currency' => 'IDR', 'language' => 'Bahasa Indonesia'],
            'is_featured' => false,
            'images' => ['destinations/bandung-1.jpg', 'destinations/bandung-2.jpg'],
        ],
    ];

    public function run(): void
    {
        foreach ($this->destinations as $data) {
            $images = $data['images'];
            unset($data['images']);

            $destination = Destination::updateOrCreate(
                ['slug' => str($data['name'])->slug()],
                array_merge($data, [
                    'slug' => str($data['name'])->slug(),
                    'hero_image' => $images[0],
                    'status' => 'published',
                ])
            );

            $destination->images()->delete();
            foreach ($images as $index => $path) {
                $destination->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
