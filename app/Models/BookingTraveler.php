<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTraveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'full_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
