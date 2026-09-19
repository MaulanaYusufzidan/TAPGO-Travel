<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TripSeeder extends Seeder
{
    /**
     * Paket trip demo, satu per destinasi yang sudah ada.
     * Gambar memakai path storage lokal (konsisten dengan DestinationSeeder);
     * komponen trip-card/trip show memiliki fallback Unsplash bila file belum di-upload.
     */
    protected array $trips = [
        'Bali' => [
            'title' => '4D3N Bali Culture & Beach Escape',
            'category' => 'Beach & Island',
            'description' => 'Nikmati empat hari tiga malam menjelajahi sisi terbaik Bali: sawah berundak Tegalalang, pura ikonik Tanah Lot, dan sunset di pantai Seminyak. Cocok untuk pasangan maupun keluarga yang ingin liburan santai namun tetap kaya pengalaman budaya.',
            'meeting_point' => 'Bandara Internasional I Gusti Ngurah Rai, Bali',
            'duration' => '4D3N',
            'min_group_size' => 2,
            'max_group_size' => 15,
            'base_price' => 3250000,
            'rating_avg' => 4.8,
            'reviews_count' => 214,
            'is_featured' => true,
            'itinerary' => [
                ['title' => 'Tiba di Bali & Uluwatu Sunset', 'description' => 'Penjemputan di bandara, check-in hotel, sore hari menyaksikan tari Kecak di Pura Uluwatu.'],
                ['title' => 'Ubud Culture Day', 'description' => 'Sawah Tegalalang, Monkey Forest, dan pusat kerajinan seni Ubud.'],
                ['title' => 'Tanah Lot & Seminyak', 'description' => 'Kunjungan Pura Tanah Lot dilanjutkan bersantai di Pantai Seminyak.'],
                ['title' => 'Waktu Bebas & Kepulangan', 'description' => 'Waktu bebas untuk belanja oleh-oleh sebelum transfer ke bandara.'],
            ],
            'inclusions' => ['Hotel bintang 4 (3 malam)', 'Sarapan setiap hari', 'Transportasi ber-AC', 'Tiket masuk destinasi', 'Local guide berbahasa Indonesia & Inggris'],
            'exclusions' => ['Tiket pesawat pulang-pergi', 'Pengeluaran pribadi', 'Asuransi perjalanan', 'Tip untuk guide & driver'],
            'schedules' => [14, 28, 45],
        ],
        'Yogyakarta' => [
            'title' => '3D2N Yogyakarta Heritage Trail',
            'category' => 'Culture & Heritage',
            'description' => 'Jelajahi warisan budaya Yogyakarta mulai dari sunrise di Candi Borobudur, kemegahan Candi Prambanan, hingga suasana Malioboro di malam hari. Perjalanan ini dirancang untuk pecinta sejarah dan fotografi.',
            'meeting_point' => 'Stasiun Yogyakarta Tugu',
            'duration' => '3D2N',
            'min_group_size' => 2,
            'max_group_size' => 20,
            'base_price' => 1950000,
            'rating_avg' => 4.7,
            'reviews_count' => 156,
            'is_featured' => true,
            'itinerary' => [
                ['title' => 'Sunrise Borobudur', 'description' => 'Menyaksikan matahari terbit dari area Candi Borobudur, dilanjutkan sarapan tradisional.'],
                ['title' => 'Prambanan & Keraton', 'description' => 'Kunjungan Candi Prambanan dan Keraton Yogyakarta.'],
                ['title' => 'Malioboro & Kuliner Gudeg', 'description' => 'Belanja di Malioboro dan makan malam gudeg khas Yogyakarta.'],
            ],
            'inclusions' => ['Hotel bintang 3 (2 malam)', 'Sarapan setiap hari', 'Tiket sunrise Borobudur', 'Transportasi ber-AC', 'Local guide'],
            'exclusions' => ['Tiket kereta/pesawat', 'Makan siang & malam (kecuali disebutkan)', 'Pengeluaran pribadi'],
            'schedules' => [10, 24, 38],
        ],
        'Raja Ampat' => [
            'title' => '5D4N Raja Ampat Diving Expedition',
            'category' => 'Adventure',
            'description' => 'Ekspedisi menyelam di salah satu surga bawah laut terbaik dunia. Termasuk island hopping, snorkeling di titik-titik terbaik, dan kesempatan melihat keanekaragaman hayati laut Raja Ampat.',
            'meeting_point' => 'Bandara Domine Eduard Osok, Sorong',
            'duration' => '5D4N',
            'min_group_size' => 4,
            'max_group_size' => 12,
            'base_price' => 8750000,
            'rating_avg' => 4.9,
            'reviews_count' => 87,
            'is_featured' => true,
            'itinerary' => [
                ['title' => 'Sorong ke Waisai', 'description' => 'Perjalanan speedboat menuju Waisai, check-in resort tepi pantai.'],
                ['title' => 'Piaynemo & Island Hopping', 'description' => 'Trekking ke viewpoint Piaynemo dan island hopping ke beberapa pulau karst.'],
                ['title' => 'Diving Wayag', 'description' => 'Dua sesi diving/snorkeling di kawasan Wayag.'],
                ['title' => 'Pulau Arborek', 'description' => 'Snorkeling bersama manta ray di sekitar Pulau Arborek.'],
                ['title' => 'Kepulangan', 'description' => 'Transfer kembali ke Sorong untuk penerbangan pulang.'],
            ],
            'inclusions' => ['Resort tepi pantai (4 malam)', 'Semua makan (full board)', 'Peralatan snorkeling', '4x sesi diving/snorkeling', 'Speedboat antar pulau'],
            'exclusions' => ['Tiket pesawat ke Sorong', 'Sertifikasi & sewa alat diving profesional', 'PIN masuk kawasan konservasi (dibayar di lokasi)'],
            'schedules' => [21, 50],
        ],
        'Bromo Tengger Semeru' => [
            'title' => '2D1N Bromo Sunrise Jeep Tour',
            'category' => 'Nature & Hiking',
            'description' => 'Petualangan singkat namun tak terlupakan menyaksikan sunrise dari Penanjakan, menjelajah lautan pasir dengan jeep 4x4, dan trekking menuju kawah Bromo.',
            'meeting_point' => 'Terminal Wisata Cemoro Lawang, Probolinggo',
            'duration' => '2D1N',
            'min_group_size' => 2,
            'max_group_size' => 25,
            'base_price' => 850000,
            'rating_avg' => 4.6,
            'reviews_count' => 302,
            'is_featured' => false,
            'itinerary' => [
                ['title' => 'Penjemputan & Malam di Cemoro Lawang', 'description' => 'Check-in penginapan dekat kawasan Bromo, istirahat untuk persiapan sunrise trip dini hari.'],
                ['title' => 'Sunrise Penanjakan & Kawah Bromo', 'description' => 'Jeep menuju Penanjakan untuk sunrise, dilanjutkan lautan pasir dan trekking ke kawah Bromo.'],
            ],
            'inclusions' => ['Penginapan 1 malam', 'Jeep 4x4 (sharing)', 'Tiket masuk Taman Nasional', 'Guide lokal'],
            'exclusions' => ['Makan siang & malam', 'Sewa kuda menuju kawah', 'Pengeluaran pribadi'],
            'schedules' => [7, 15, 22, 30],
        ],
        'Labuan Bajo' => [
            'title' => '3D2N Komodo Island Sailing Trip',
            'category' => 'Adventure',
            'description' => 'Berlayar menuju Taman Nasional Komodo, bertemu langsung dengan komodo di habitat aslinya, snorkeling di Pink Beach, dan sunset sailing yang memukau.',
            'meeting_point' => 'Pelabuhan Labuan Bajo',
            'duration' => '3D2N',
            'min_group_size' => 2,
            'max_group_size' => 18,
            'base_price' => 4100000,
            'rating_avg' => 4.8,
            'reviews_count' => 129,
            'is_featured' => true,
            'itinerary' => [
                ['title' => 'Pulau Komodo & Pulau Padar', 'description' => 'Trekking melihat komodo, dilanjutkan mendaki viewpoint ikonik Pulau Padar.'],
                ['title' => 'Pink Beach & Manta Point', 'description' => 'Snorkeling di Pink Beach dan berenang bersama manta ray.'],
                ['title' => 'Sunset Sailing & Kepulangan', 'description' => 'Berlayar santai menikmati sunset sebelum kembali ke pelabuhan.'],
            ],
            'inclusions' => ['Kapal phinisi (2 malam on-board)', 'Semua makan selama trip', 'Peralatan snorkeling', 'Tiket masuk Taman Nasional Komodo', 'Ranger guide'],
            'exclusions' => ['Tiket pesawat ke Labuan Bajo', 'Sewa kamera underwater', 'Tip crew kapal'],
            'schedules' => [12, 26, 40],
        ],
        'Bandung' => [
            'title' => '2D1N Bandung Nature & Culinary Getaway',
            'category' => 'Culinary',
            'description' => 'Liburan akhir pekan santai ke Bandung: udara sejuk kawah Tangkuban Perahu, kebun teh Ciwidey, dan wisata kuliner khas Sunda di malam hari.',
            'meeting_point' => 'Stasiun Bandung',
            'duration' => '2D1N',
            'min_group_size' => 2,
            'max_group_size' => 16,
            'base_price' => 1150000,
            'rating_avg' => 4.5,
            'reviews_count' => 98,
            'is_featured' => false,
            'itinerary' => [
                ['title' => 'Tangkuban Perahu & Ciwidey', 'description' => 'Kunjungan kawah Tangkuban Perahu dan kebun teh Ciwidey, sore hari kuliner malam di kota Bandung.'],
                ['title' => 'Factory Outlet & Kepulangan', 'description' => 'Waktu bebas belanja di kawasan factory outlet sebelum kembali ke stasiun.'],
            ],
            'inclusions' => ['Hotel bintang 3 (1 malam)', 'Sarapan', 'Transportasi ber-AC', 'Tiket masuk destinasi'],
            'exclusions' => ['Tiket kereta/travel ke Bandung', 'Makan siang & malam', 'Pengeluaran pribadi'],
            'schedules' => [9, 20, 33],
        ],
    ];

    public function run(): void
    {
        foreach ($this->trips as $destinationName => $data) {
            $destination = Destination::where('name', $destinationName)->first();

            if (! $destination) {
                continue;
            }

            $category = Category::where('name', $data['category'])->first();

            $slug = Str::slug($data['title']);

            $trip = Trip::updateOrCreate(
                ['slug' => $slug],
                [
                    'destination_id' => $destination->id,
                    'category_id' => $category?->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'meeting_point' => $data['meeting_point'],
                    'duration' => $data['duration'],
                    'min_group_size' => $data['min_group_size'],
                    'max_group_size' => $data['max_group_size'],
                    'base_price' => $data['base_price'],
                    'rating_avg' => $data['rating_avg'],
                    'reviews_count' => $data['reviews_count'],
                    'is_featured' => $data['is_featured'],
                    'status' => 'published',
                ]
            );

            // Reset related rows so re-seeding stays idempotent.
            $trip->images()->delete();
            $trip->itineraries()->delete();
            $trip->inclusions()->delete();
            $trip->exclusions()->delete();
            $trip->schedules()->delete();

            foreach (range(1, 3) as $i) {
                $trip->images()->create([
                    'image_path' => 'trips/' . $slug . '-' . $i . '.jpg',
                    'caption' => $data['title'],
                    'sort_order' => $i,
                ]);
            }

            foreach ($data['itinerary'] as $index => $day) {
                $trip->itineraries()->create([
                    'day_number' => $index + 1,
                    'title' => $day['title'],
                    'description' => $day['description'],
                    'sort_order' => $index,
                ]);
            }

            foreach ($data['inclusions'] as $index => $item) {
                $trip->inclusions()->create(['item' => $item, 'sort_order' => $index]);
            }

            foreach ($data['exclusions'] as $index => $item) {
                $trip->exclusions()->create(['item' => $item, 'sort_order' => $index]);
            }

            foreach ($data['schedules'] as $daysFromNow) {
                $trip->schedules()->create([
                    'date' => now()->addDays($daysFromNow)->toDateString(),
                    'departure_time' => '07:00',
                    'return_time' => null,
                    'capacity' => $data['max_group_size'],
                    'booked_seats' => random_int(0, max(0, intdiv($data['max_group_size'], 3))),
                    'price' => $data['base_price'],
                    'status' => 'available',
                ]);
            }
        }
    }
}
