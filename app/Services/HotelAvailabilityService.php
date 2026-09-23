<?php

namespace App\Services;

use App\Models\RoomInventory;
use App\Models\RoomType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * HotelAvailabilityService
 *
 * Business logic untuk mencegah overbooking kamar hotel (spec section 33,
 * 46-47). Kalau room_inventory belum ada baris untuk tanggal tertentu
 * (di luar 30 hari yang di-seed), dianggap fallback: available_rooms =
 * RoomType::quantity dan price = RoomType::base_price.
 */
class HotelAvailabilityService
{
    /**
     * @return \Illuminate\Support\Collection<int, Carbon> tiap malam dari check-in s/d check-out - 1
     */
    protected function nights(Carbon $checkIn, Carbon $checkOut): \Illuminate\Support\Collection
    {
        $dates = collect();
        for ($date = $checkIn->copy(); $date->lt($checkOut); $date->addDay()) {
            $dates->push($date->copy());
        }

        return $dates;
    }

    /**
     * Cek cepat (tanpa lock) — cocok untuk feedback awal sebelum checkout.
     */
    public function checkAvailability(RoomType $roomType, Carbon $checkIn, Carbon $checkOut, int $quantity): bool
    {
        foreach ($this->nights($checkIn, $checkOut) as $date) {
            $row = RoomInventory::where('room_type_id', $roomType->id)
                ->where('date', $date->toDateString())
                ->first();

            $available = $row ? $row->available_rooms : $roomType->quantity;

            if ($available < $quantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Hitung subtotal (harga per malam x quantity, dijumlah semua malam)
     * berdasarkan harga di room_inventory kalau ada, fallback ke base_price.
     */
    public function subtotalFor(RoomType $roomType, Carbon $checkIn, Carbon $checkOut, int $quantity): float
    {
        $subtotal = 0.0;

        foreach ($this->nights($checkIn, $checkOut) as $date) {
            $row = RoomInventory::where('room_type_id', $roomType->id)
                ->where('date', $date->toDateString())
                ->first();

            $price = $row ? (float) $row->price : (float) $roomType->base_price;
            $subtotal += $price * $quantity;
        }

        return $subtotal;
    }

    /**
     * Reservasi kamar secara atomic untuk seluruh rentang tanggal. Setiap
     * baris room_inventory di-lock (lockForUpdate) supaya dua request
     * booking bersamaan tidak sama-sama lolos melebihi kapasitas. Baris
     * yang belum ada langsung dibuat (firstOrCreate di dalam lock).
     *
     * Return true kalau semua malam berhasil direservasi, false kalau ada
     * satu saja tanggal yang kuotanya tidak cukup (transaction di-rollback
     * oleh pemanggil).
     */
    public function reserve(RoomType $roomType, Carbon $checkIn, Carbon $checkOut, int $quantity): bool
    {
        return DB::transaction(function () use ($roomType, $checkIn, $checkOut, $quantity) {
            foreach ($this->nights($checkIn, $checkOut) as $date) {
                $row = RoomInventory::where('room_type_id', $roomType->id)
                    ->where('date', $date->toDateString())
                    ->lockForUpdate()
                    ->first();

                if (! $row) {
                    $row = RoomInventory::create([
                        'room_type_id' => $roomType->id,
                        'date' => $date->toDateString(),
                        'available_rooms' => $roomType->quantity,
                        'price' => $roomType->base_price,
                    ]);
                }

                if ($row->available_rooms < $quantity) {
                    return false;
                }

                $row->decrement('available_rooms', $quantity);
            }

            return true;
        });
    }

    /**
     * Lepas kembali kamar yang sudah direservasi (booking dibatalkan/expired).
     */
    public function release(RoomType $roomType, Carbon $checkIn, Carbon $checkOut, int $quantity): void
    {
        DB::transaction(function () use ($roomType, $checkIn, $checkOut, $quantity) {
            foreach ($this->nights($checkIn, $checkOut) as $date) {
                $row = RoomInventory::where('room_type_id', $roomType->id)
                    ->where('date', $date->toDateString())
                    ->lockForUpdate()
                    ->first();

                if ($row) {
                    $row->update(['available_rooms' => min($roomType->quantity, $row->available_rooms + $quantity)]);
                }
            }
        });
    }
}
