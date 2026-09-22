<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelImageSeeder extends Seeder
{
    /**
     * Pool foto placeholder (Unsplash), pola sama dengan yang sudah dipakai
     * di PageController::hotelsData() sebelum dimigrasikan ke database.
     * Ini BUKAN foto hotel asli — cuma placeholder visual untuk development.
     */
    protected array $pool = [
        'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1582610116397-edb318620f90?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1200&q=85',
        'https://images.unsplash.com/photo-1590073242678-70ee3fc28f8e?auto=format&fit=crop&w=1200&q=85',
    ];

    public function run(): void
    {
        Hotel::all()->each(function (Hotel $hotel, int $index) {
            $hotel->images()->delete();

            $count = 6 + ($index % 3); // 6-8 gambar per hotel
            for ($i = 0; $i < $count; $i++) {
                $hotel->images()->create([
                    'image_path' => $this->pool[($index + $i) % count($this->pool)],
                    'caption' => $hotel->name.' - foto '.($i + 1),
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        });
    }
}
