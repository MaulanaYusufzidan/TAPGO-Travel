<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotelBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'hotel_id',
        'check_in',
        'check_out',
        'guests',
        'rooms',
        'subtotal',
        'tax',
        'service_fee',
        'discount',
        'total',
        'status',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_request',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'guests' => 'integer',
        'rooms' => 'integer',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookingRooms(): HasMany
    {
        return $this->hasMany(HotelBookingRoom::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(HotelPayment::class);
    }

    /**
     * Jumlah malam, dihitung dari check_in/check_out (spec section 32-33).
     */
    public function nights(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
