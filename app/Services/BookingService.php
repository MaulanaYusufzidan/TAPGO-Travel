<?php

namespace App\Services;

use App\Models\Schedule;

/**
 * BookingService
 *
 * Business logic kompleks booking ditempatkan di Service Layer,
 * bukan Controller (PRD section 29 Laravel Architecture).
 *
 * PRD section 18 Checkout Requirements:
 * "Total harus dihitung oleh backend. Frontend tidak boleh
 * dipercaya untuk menentukan harga akhir."
 */
class BookingService
{
    /**
     * Hitung price breakdown untuk sebuah schedule + quantity.
     * Harga SELALU diambil dari data schedule di database (bukan
     * dari input user), supaya tidak bisa dimanipulasi dari frontend.
     *
     * @return array{unit_price: float, quantity: int, subtotal: float, service_fee: float, discount: float, total: float}
     */
    public function calculatePrice(Schedule $schedule, int $quantity, float $discount = 0): array
    {
        $unitPrice = (float) $schedule->price;
        $subtotal = $unitPrice * $quantity;

        $feePercentage = (float) config('booking.service_fee_percentage', 2);
        $serviceFee = round($subtotal * ($feePercentage / 100), 2);

        $discount = min($discount, $subtotal + $serviceFee);
        $total = max(0, $subtotal + $serviceFee - $discount);

        return [
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'discount' => $discount,
            'total' => $total,
        ];
    }
}
