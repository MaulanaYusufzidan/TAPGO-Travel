<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'location' => $name,
            'description' => fake()->paragraphs(3, true),
            'hero_image' => 'destinations/placeholder.jpg',
            'things_to_do' => fake()->randomElements([
                'Wisata kuliner', 'Trekking', 'Snorkeling', 'Fotografi', 'Belanja oleh-oleh',
                'Wisata sejarah', 'Diving', 'Camping', 'Wisata budaya',
            ], 3),
            'travel_information' => [
                'best_time' => fake()->randomElement(['Januari - Maret', 'April - Juni', 'Juli - September', 'Sepanjang tahun']),
                'currency' => 'IDR',
                'language' => 'Bahasa Indonesia',
            ],
            'is_featured' => fake()->boolean(30),
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
