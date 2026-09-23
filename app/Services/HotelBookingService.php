<?php

namespace App\Services;

use App\Models\RoomType;
use Illuminate\Support\Carbon;

/**
 * HotelBookingService
 *
 * PRD section 34 (Price Calculation): "Semua harus dihitung dari backend.
 * JavaScript hanya digunakan untuk memberikan preview realtime." Harga
 * SELALU diambil dari room_inventory/RoomType di database, bukan dari
 * input user, supaya tidak bisa dimanipulasi dari frontend.
 */
class HotelBookingService
{
    public function __construct(protected HotelAvailabilityService $availability)
    {
    }

    /**
     * @return array{nights: int, quantity: int, subtotal: float, tax: float, service_fee: float, discount: float, total: float}
     */
    public function calculatePrice(RoomType $roomType, Carbon $checkIn, Carbon $checkOut, int $quantity, float $discount = 0): array
    {
        $nights = $checkIn->diffInDays($checkOut);
        $subtotal = $this->availability->subtotalFor($roomType, $checkIn, $checkOut, $quantity);

        $taxPercentage = (float) config('booking.tax_percentage', 11);
        $feePercentage = (float) config('booking.service_fee_percentage', 2);

        $tax = round($subtotal * ($taxPercentage / 100), 2);
        $serviceFee = round($subtotal * ($feePercentage / 100), 2);

        $discount = min($discount, $subtotal + $tax + $serviceFee);
        $total = max(0, $subtotal + $tax + $serviceFee - $discount);

        return [
            'nights' => $nights,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'service_fee' => $serviceFee,
            'discount' => $discount,
            'total' => $total,
        ];
    }
}
