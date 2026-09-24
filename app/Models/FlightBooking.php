<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'flight_offer_id',
        'passengers',
        'subtotal',
        'tax',
        'service_fee',
        'discount',
        'total',
        'status',
        'contact_email',
        'contact_phone',
    ];

    protected $casts = [
        'passengers' => 'integer',
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

    public function flightOffer(): BelongsTo
    {
        return $this->belongsTo(FlightOffer::class);
    }

    public function passengerDetails(): HasMany
    {
        return $this->hasMany(FlightBookingPassenger::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FlightPayment::class);
    }
}
