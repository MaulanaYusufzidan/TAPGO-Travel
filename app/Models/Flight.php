<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline_id',
        'flight_number',
        'origin_code',
        'origin_city',
        'destination_code',
        'destination_city',
        'departure_at',
        'arrival_at',
        'duration_minutes',
        'stops',
        'travel_class',
        'wifi',
        'meal',
        'baggage_kg',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'duration_minutes' => 'integer',
        'stops' => 'integer',
        'wifi' => 'boolean',
        'meal' => 'boolean',
        'baggage_kg' => 'integer',
    ];

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function getStopsLabelAttribute(): string
    {
        return match ($this->stops) {
            0 => 'Non-stop',
            1 => '1 Stop',
            default => "{$this->stops}+ Stop",
        };
    }

    public function getDurationLabelAttribute(): string
    {
        $h = intdiv($this->duration_minutes, 60);
        $m = $this->duration_minutes % 60;

        return "{$h}h {$m}m";
    }
}
