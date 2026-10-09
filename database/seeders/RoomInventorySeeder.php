<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RoomInventorySeeder extends Seeder
{
    /**
     * Isi 30 hari ke depan per tipe kamar, harga sedikit fluktuatif
     * (weekend lebih mahal) dan availability acak (spec section 10, 33-34).
     */
    public function run(): void
    {
        RoomType::with('hotel')->get()->each(function (RoomType $roomType) {
            $roomType->inventory()->delete();

            $rows = [];
            $start = Carbon::today();

            for ($i = 0; $i < 30; $i++) {
                $date = $start->copy()->addDays($i);
                $isWeekend = $date->isWeekend();

                $price = $roomType->base_price * ($isWeekend ? 1.15 : 1.0);
                $price = $roomType->hotel->priceAfterDiscount($price);
                $available = fake()->numberBetween(0, $roomType->quantity);

                $rows[] = [
                    'room_type_id' => $roomType->id,
                    'date' => $date->toDateString(),
                    'available_rooms' => $available,
                    'price' => round($price, -3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $roomType->inventory()->insert($rows);
        });
    }
}
