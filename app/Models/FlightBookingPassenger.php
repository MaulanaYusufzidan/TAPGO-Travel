<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightBookingPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_booking_id',
        'first_name',
        'last_name',
        'passport_number',
        'passport_expiry',
        'date_of_birth',
        'gender',
        'nationality',
    ];

    protected $casts = [
        'passport_expiry' => 'date',
        'date_of_birth' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAgeAttribute(): int
    {
        return (int) $this->date_of_birth->age;
    }
}
