<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'date',
        'departure_time',
        'return_time',
        'capacity',
        'booked_seats',
        'price',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_seats);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Cek apakah schedule ini masih bisa menampung sejumlah seat.
     * PRD section 16: Overbooking Prevention.
     */
    public function hasCapacityFor(int $quantity): bool
    {
        return $this->status === 'available' && $this->available_seats >= $quantity;
    }
}
