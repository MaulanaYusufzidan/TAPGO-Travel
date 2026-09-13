<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

/**
 * AvailabilityService
 *
 * Business logic untuk mencegah overbooking (PRD section 16).
 * Business logic kompleks ditempatkan di Service Layer, bukan
 * di Controller (PRD section 29 Laravel Architecture).
 */
class AvailabilityService
{
    /**
     * Cek cepat (tanpa lock) apakah schedule masih bisa menampung
     * sejumlah quantity. Cocok untuk feedback awal ke user sebelum
     * checkout final.
     */
    public function checkAvailability(Schedule $schedule, int $quantity): bool
    {
        $fresh = $schedule->fresh();

        return $fresh->hasCapacityFor($quantity);
    }

    /**
     * Reservasi kursi secara atomic. Row schedule di-lock supaya dua
     * request booking yang masuk bersamaan tidak bisa sama-sama lolos
     * melebihi capacity (PRD section 16: Overbooking Prevention,
     * "Booking creation harus menggunakan database transaction").
     *
     * Return true kalau reservasi berhasil, false kalau kuota tidak cukup.
     */
    public function reserve(Schedule $schedule, int $quantity): bool
    {
        return DB::transaction(function () use ($schedule, $quantity) {
            $locked = Schedule::where('id', $schedule->id)->lockForUpdate()->first();

            if (! $locked || ! $locked->hasCapacityFor($quantity)) {
                return false;
            }

            $locked->booked_seats += $quantity;

            if ($locked->booked_seats >= $locked->capacity) {
                $locked->status = 'full';
            }

            $locked->save();

            return true;
        });
    }

    /**
     * Lepas kembali kursi yang sudah direservasi, misalnya saat booking
     * dibatalkan/expired.
     */
    public function release(Schedule $schedule, int $quantity): void
    {
        DB::transaction(function () use ($schedule, $quantity) {
            $locked = Schedule::where('id', $schedule->id)->lockForUpdate()->first();

            if (! $locked) {
                return;
            }

            $locked->booked_seats = max(0, $locked->booked_seats - $quantity);

            if ($locked->status === 'full' && $locked->booked_seats < $locked->capacity) {
                $locked->status = 'available';
            }

            $locked->save();
        });
    }
}
