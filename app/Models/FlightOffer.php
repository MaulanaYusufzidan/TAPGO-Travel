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

    public function scopeRoute($query, ?string $origin, ?string $destination)
    {
        return $query->whereHas('departureFlight', function ($q) use ($origin, $destination) {
            if ($origin) {
                $q->where(function ($q2) use ($origin) {
                    $q2->where('origin_city', 'like', "%{$origin}%")->orWhere('origin_code', $origin);
                });
            }
            if ($destination) {
                $q->where(function ($q2) use ($destination) {
                    $q2->where('destination_city', 'like', "%{$destination}%")->orWhere('destination_code', $destination);
                });
            }
        });
    }

    public function scopeDepartureDate($query, ?string $date)
    {
        if (! $date) {
            return $query;
        }

        return $query->whereHas('departureFlight', fn ($q) => $q->whereDate('departure_at', $date));
    }

    public function scopeStops($query, string $relation, array $stopKeys)
    {
        $stopKeys = array_filter($stopKeys);
        if (empty($stopKeys)) {
            return $query;
        }

        return $query->whereHas($relation, function ($q) use ($stopKeys) {
            $q->where(function ($q2) use ($stopKeys) {
                foreach ($stopKeys as $key) {
                    match ($key) {
                        'direct' => $q2->orWhere('stops', 0),
                        '1_stop' => $q2->orWhere('stops', 1),
                        '2_plus' => $q2->orWhere('stops', '>=', 2),
                        default => null,
                    };
                }
            });
        });
    }

    /**
     * $bucket: 'before_6am' | '6am_12pm' | '12pm_6pm' | 'after_6pm'
     */
    public function scopeTimeBucket($query, string $relation, ?string $bucket)
    {
        if (! $bucket) {
            return $query;
        }

        [$start, $end] = match ($bucket) {
            'before_6am' => ['00:00:00', '06:00:00'],
            '6am_12pm' => ['06:00:00', '12:00:00'],
            '12pm_6pm' => ['12:00:00', '18:00:00'],
            'after_6pm' => ['18:00:00', '23:59:59'],
            default => [null, null],
        };

        if (! $start) {
            return $query;
        }

        return $query->whereHas($relation, fn ($q) => $q->whereTime('departure_at', '>=', $start)->whereTime('departure_at', '<=', $end));
    }

    public function scopePriceBetween($query, ?int $min, ?int $max)
    {
        if (! $min && ! $max) {
            return $query;
        }
        if ($min) {
            $query->where('base_price', '>=', $min);
        }
        if ($max) {
            $query->where('base_price', '<=', $max);
        }

        return $query;
    }

    public function scopeAirlineIds($query, array $airlineIds)
    {
        $airlineIds = array_filter($airlineIds);
        if (empty($airlineIds)) {
            return $query;
        }

        return $query->whereHas('departureFlight', fn ($q) => $q->whereIn('airline_id', $airlineIds));
    }

    public function scopeFacility($query, string $column, bool $only = true)
    {
        if (! $only) {
            return $query;
        }

        return $query->whereHas('departureFlight', fn ($q) => $q->where($column, true));
    }
}
