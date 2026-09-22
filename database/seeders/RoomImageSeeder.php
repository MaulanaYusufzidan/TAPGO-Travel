<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomImageSeeder extends Seeder
{
    protected array $pool = [
        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1000&q=85',
        'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1000&q=85',
        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1000&q=85',
        'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=1000&q=85',
        'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&w=1000&q=85',
        'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=1000&q=85',
    ];

    public function run(): void
    {
        RoomType::all()->each(function (RoomType $roomType, int $index) {
            $roomType->images()->delete();

            $count = fake()->numberBetween(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $roomType->images()->create([
                    'image_path' => $this->pool[($index + $i) % count($this->pool)],
                    'caption' => $roomType->name.' - foto '.($i + 1),
                    'sort_order' => $i,
                ]);
            }
        });
    }
}
