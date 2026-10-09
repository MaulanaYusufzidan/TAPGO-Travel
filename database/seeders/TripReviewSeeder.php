<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Trip;
use App\Models\TripReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TripReviewSeeder extends Seeder
{
    protected array $reviewerNames = [
        'Dewi Anggraini', 'Budi Santoso', 'Siti Rahma', 'Andi Wijaya',
        'Putri Lestari', 'Rizky Pratama', 'Nadia Salsabila', 'Fajar Nugroho',
    ];

    protected array $comments = [
        'Tur-nya rapi banget, guide ramah dan informatif. Worth it!',
        'Itinerary sesuai jadwal, gak ada drama. Recommended buat keluarga.',
        'Pemandangan luar biasa, transportasi nyaman selama perjalanan.',
        'Pelayanan memuaskan, cuma waktu di beberapa spot agak buru-buru.',
        'Semua sesuai deskripsi, harga sepadan sama pengalamannya.',
        'Guide-nya asik, banyak spot foto bagus yang gak ada di itinerary standar.',
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

        Trip::all()->each(function (Trip $trip) use ($reviewers) {
            $trip->reviews()->delete();

            $count = fake()->numberBetween(3, 6);
            $chosen = $reviewers->random(min($count, $reviewers->count()));

            foreach ($chosen as $reviewer) {
                TripReview::create([
                    'trip_id' => $trip->id,
                    'user_id' => $reviewer->id,
                    // Trip pakai skala bintang 1-5 (beda dari hotel yang 1-10).
                    'rating' => fake()->randomElement([5, 5, 5, 4, 4, 3]),
                    'comment' => fake()->randomElement($this->comments),
                    'status' => 'published',
                ]);
            }

            $trip->update([
                'rating_avg' => round($trip->reviews()->avg('rating'), 2) ?? 0,
                'reviews_count' => $trip->reviews()->count(),
            ]);
        });
    }
}
