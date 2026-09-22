<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * 10 hotel dummy (brand TAPGO sendiri, bukan brand asli) tersebar di
     * destinasi populer Indonesia (spec section 20).
     */
    protected array $hotels = [
        [
            'name' => 'Grand TAPGO Bali',
            'hotel_type' => 'Resort',
            'star_rating' => 5,
            'city' => 'Badung',
            'province' => 'Bali',
            'address' => 'Jl. Kayu Aya No. 8, Seminyak',
            'latitude' => -8.6905,
            'longitude' => 115.1673,
            'short_description' => 'Resort tepi pantai dengan kolam infinity menghadap Samudra Hindia.',
            'description' => 'Grand TAPGO Bali menawarkan pengalaman menginap premium di jantung Seminyak, dengan akses langsung ke pantai, kolam infinity, dan spa tradisional Bali.',
            'is_featured' => true,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Parking', 'Restaurant', 'Spa', 'Airport Shuttle', 'Beachfront', 'Bar'],
        ],
        [
            'name' => 'TAPGO Ubud Retreat',
            'hotel_type' => 'Boutique Hotel',
            'star_rating' => 4,
            'city' => 'Gianyar',
            'province' => 'Bali',
            'address' => 'Jl. Raya Sanggingan, Ubud',
            'latitude' => -8.5069,
            'longitude' => 115.2625,
            'short_description' => 'Villa boutique dikelilingi sawah dan hutan tropis Ubud.',
            'description' => 'Suasana tenang khas Ubud, dengan villa kayu, kolam renang alami, dan kelas yoga setiap pagi.',
            'is_featured' => true,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Restaurant', 'Spa', 'Airport Shuttle', 'Parking'],
        ],
        [
            'name' => 'TAPGO Jakarta Sudirman',
            'hotel_type' => 'Business Hotel',
            'star_rating' => 5,
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'address' => 'Jl. Jend. Sudirman Kav. 45',
            'latitude' => -6.2241,
            'longitude' => 106.8090,
            'short_description' => 'Hotel bisnis modern di kawasan segitiga emas Jakarta.',
            'description' => 'Lokasi strategis untuk perjalanan bisnis, dilengkapi meeting room, gym 24 jam, dan rooftop bar.',
            'is_featured' => true,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Parking', 'Restaurant', 'Fitness Center', '24 Hour Front Desk', 'Meeting Room', 'Bar'],
        ],
        [
            'name' => 'TAPGO Kemang Suites',
            'hotel_type' => 'Boutique Hotel',
            'star_rating' => 4,
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'address' => 'Jl. Kemang Raya No. 21',
            'latitude' => -6.2607,
            'longitude' => 106.8133,
            'short_description' => 'Suite nyaman di kawasan kuliner dan hiburan Kemang.',
            'description' => 'Dekat dengan berbagai kafe dan restoran Kemang, cocok untuk staycation maupun kerja remote.',
            'is_featured' => false,
            'amenities' => ['Free WiFi', 'Parking', 'Restaurant', '24 Hour Front Desk', 'Room Service'],
        ],
        [
            'name' => 'TAPGO Bandung Dago Hills',
            'hotel_type' => 'Resort',
            'star_rating' => 4,
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'address' => 'Jl. Dago Atas No. 99',
            'latitude' => -6.8656,
            'longitude' => 107.6134,
            'short_description' => 'Resort sejuk di perbukitan Dago dengan pemandangan kota Bandung.',
            'description' => 'Udara sejuk khas Bandung Utara, dilengkapi kolam air panas alami dan area bonfire malam hari.',
            'is_featured' => false,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Parking', 'Restaurant', 'Fitness Center', 'Family Rooms'],
        ],
        [
            'name' => 'TAPGO Yogyakarta Heritage',
            'hotel_type' => 'Heritage Hotel',
            'star_rating' => 4,
            'city' => 'Yogyakarta',
            'province' => 'Daerah Istimewa Yogyakarta',
            'address' => 'Jl. Malioboro No. 60',
            'latitude' => -7.7928,
            'longitude' => 110.3656,
            'short_description' => 'Hotel bergaya Jawa klasik, tepat di ujung Jalan Malioboro.',
            'description' => 'Arsitektur joglo dipadu fasilitas modern, lokasi jalan kaki ke Malioboro dan Keraton Yogyakarta.',
            'is_featured' => true,
            'amenities' => ['Free WiFi', 'Parking', 'Restaurant', '24 Hour Front Desk', 'Airport Shuttle'],
        ],
        [
            'name' => 'TAPGO Lombok Beach Resort',
            'hotel_type' => 'Resort',
            'star_rating' => 5,
            'city' => 'Lombok Utara',
            'province' => 'Nusa Tenggara Barat',
            'address' => 'Jl. Raya Senggigi Km 8',
            'latitude' => -8.4890,
            'longitude' => 116.0405,
            'short_description' => 'Resort pantai eksklusif menghadap Selat Lombok dan Gunung Rinjani.',
            'description' => 'Villa privat dengan kolam renang sendiri, akses langsung ke pantai pasir putih Senggigi.',
            'is_featured' => true,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Restaurant', 'Spa', 'Beachfront', 'Airport Shuttle', 'Bar'],
        ],
        [
            'name' => 'TAPGO Surabaya City Hotel',
            'hotel_type' => 'Business Hotel',
            'star_rating' => 3,
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'address' => 'Jl. Basuki Rahmat No. 12',
            'latitude' => -7.2650,
            'longitude' => 112.7431,
            'short_description' => 'Hotel praktis di pusat kota Surabaya, dekat pusat perbelanjaan.',
            'description' => 'Pilihan hemat untuk perjalanan bisnis maupun transit, dekat stasiun dan mal utama Surabaya.',
            'is_featured' => false,
            'amenities' => ['Free WiFi', 'Parking', '24 Hour Front Desk', 'Restaurant'],
        ],
        [
            'name' => 'TAPGO Bogor Highland',
            'hotel_type' => 'Resort',
            'star_rating' => 4,
            'city' => 'Bogor',
            'province' => 'Jawa Barat',
            'address' => 'Jl. Raya Puncak Km 77',
            'latitude' => -6.7009,
            'longitude' => 106.9896,
            'short_description' => 'Resort udara sejuk di kawasan Puncak dengan kebun teh di sekitarnya.',
            'description' => 'Cocok untuk liburan keluarga, dikelilingi kebun teh dan taman bermain outdoor.',
            'is_featured' => false,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Parking', 'Restaurant', 'Family Rooms', 'Pet Friendly'],
        ],
        [
            'name' => 'TAPGO Nusa Dua Grand',
            'hotel_type' => 'Resort',
            'star_rating' => 5,
            'city' => 'Badung',
            'province' => 'Bali',
            'address' => 'Kawasan ITDC, Nusa Dua',
            'latitude' => -8.7975,
            'longitude' => 115.2280,
            'short_description' => 'Resort mewah di kawasan Nusa Dua dengan pantai privat.',
            'description' => 'Pengalaman menginap ultra-premium dengan butler service, private beach, dan 5 pilihan restoran.',
            'is_featured' => true,
            'amenities' => ['Free WiFi', 'Swimming Pool', 'Restaurant', 'Spa', 'Fitness Center', 'Beachfront', 'Bar', 'Meeting Room'],
        ],
    ];

    public function run(): void
    {
        foreach ($this->hotels as $data) {
            $amenityNames = $data['amenities'];
            unset($data['amenities']);

            $hotel = Hotel::updateOrCreate(
                ['slug' => str($data['name'])->slug()],
                array_merge($data, [
                    'slug' => str($data['name'])->slug(),
                    'country' => 'Indonesia',
                    'check_in_time' => '14:00',
                    'check_out_time' => '12:00',
                    'phone' => '+62 21 '.fake()->numerify('####-####'),
                    'email' => 'info@'.str($data['name'])->slug().'.tapgo.travel',
                    'status' => 'published',
                ])
            );

            $amenityIds = Amenity::whereIn('name', $amenityNames)->pluck('id');
            $hotel->amenities()->sync($amenityIds);
        }
    }
}
