<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelBookingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_booking_id',
        'room_type_id',
        'room_rate_plan_id',
        'quantity',
        'price_per_night',
        'nights',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_per_night' => 'decimal:2',
        'nights' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function ratePlan(): BelongsTo
    {
        return $this->belongsTo(RoomRatePlan::class, 'room_rate_plan_id');
    }
}
