<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    /**
     * Reviewer demo (bukan user asli), dipakai bergantian di semua hotel.
     * hotel_booking_id sengaja null karena belum ada booking sungguhan yang
     * diseed — aturan "review hanya dari booking selesai" berlaku di level
     * aplikasi begitu flow booking hotel sudah jalan.
     */
    protected array $reviewerNames = [
        'Dewi Anggraini', 'Budi Santoso', 'Siti Rahma', 'Andi Wijaya',
        'Putri Lestari', 'Rizky Pratama', 'Nadia Salsabila', 'Fajar Nugroho',
    ];

    protected array $comments = [
        ['title' => 'Menginap yang sangat nyaman', 'comment' => 'Kamarnya bersih, staf ramah, dan lokasinya strategis. Pasti balik lagi kalau ke sini.'],
        ['title' => 'Sesuai ekspektasi', 'comment' => 'Fasilitas lengkap sesuai foto, sarapan enak, cuma parkir agak sempit pas weekend.'],
        ['title' => 'Worth it untuk harganya', 'comment' => 'Buat harga segini, fasilitasnya udah lebih dari cukup. Proses check-in juga cepat.'],
        ['title' => 'Pemandangan luar biasa', 'comment' => 'View dari kamar bagus banget, kolam renangnya juga bersih dan luas.'],
        ['title' => 'Pelayanan ramah', 'comment' => 'Staf front desk membantu banget waktu kami minta late check-out. Terima kasih TAPGO!'],
        ['title' => 'Lokasi sangat strategis', 'comment' => 'Dekat ke mana-mana, tinggal jalan kaki ke beberapa tempat wisata dan kuliner.'],
    ];

    public function run(): void
    {
        $customerRoleId = Role::where('slug', 'customer')->value('id');

        $reviewers = collect($this->reviewerNames)->map(function (string $name) use ($customerRoleId) {
            $email = str($name)->slug().'@mail.tapgo.demo';

            return User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role_id' => $customerRoleId,
                    'email_verified_at' => now(),
                ]
            );
        });

        Hotel::all()->each(function (Hotel $hotel) use ($reviewers) {
            $hotel->reviews()->delete();

            $count = fake()->numberBetween(3, 6);
            $chosenReviewers = $reviewers->random(min($count, $reviewers->count()));

            foreach ($chosenReviewers as $reviewer) {
                $template = fake()->randomElement($this->comments);
                $overall = fake()->numberBetween(7, 10);

                Review::create([
                    'hotel_id' => $hotel->id,
                    'user_id' => $reviewer->id,
                    'hotel_booking_id' => null,
                    'rating' => $overall,
                    'cleanliness_rating' => fake()->numberBetween(max(6, $overall - 1), 10),
                    'location_rating' => fake()->numberBetween(max(6, $overall - 1), 10),
                    'service_rating' => fake()->numberBetween(max(6, $overall - 1), 10),
                    'value_rating' => fake()->numberBetween(max(6, $overall - 1), 10),
                    'title' => $template['title'],
                    'comment' => $template['comment'],
                    'status' => 'published',
                ]);
            }

            $hotel->update([
                'rating_avg' => round($hotel->reviews()->avg('rating'), 2) ?? 0,
                'reviews_count' => $hotel->reviews()->count(),
            ]);
        });
    }
}
