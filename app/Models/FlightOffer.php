<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_flight_id',
        'return_flight_id',
        'base_price',
        'discount_percentage',
        'refundable',
        'seats_available',
        'status',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'refundable' => 'boolean',
        'seats_available' => 'integer',
    ];

    public function departureFlight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'departure_flight_id');
    }

    public function returnFlight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'return_flight_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(FlightBooking::class);
    }

    public function isRoundTrip(): bool
    {
        return ! is_null($this->return_flight_id);
    }

    public function getFinalPriceAttribute(): float
    {
        if (! $this->discount_percentage) {
            return (float) $this->base_price;
        }

        return round((float) $this->base_price * (1 - $this->discount_percentage / 100), -2);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
