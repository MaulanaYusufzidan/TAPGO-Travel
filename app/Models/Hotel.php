<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'hotel_type',
        'star_rating',
        'address',
        'city',
        'province',
        'country',
        'latitude',
        'longitude',
        'check_in_time',
        'check_out_time',
        'phone',
        'email',
        'website',
        'rating_avg',
        'reviews_count',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'star_rating' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'rating_avg' => 'decimal:2',
        'reviews_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * Route model binding pakai slug: /hotels/{slug} (spec section 4 & 26).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(HotelImage::class)->where('is_primary', true);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'amenity_hotel');
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    public function policy(): HasOne
    {
        return $this->hasOne(HotelPolicy::class);
    }

    public function nearbyPlaces(): HasMany
    {
        return $this->hasMany(NearbyPlace::class);
    }

    public function hotelBookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedBy(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('province', 'like', "%{$term}%");
        });
    }

    public function scopeMinRating($query, ?float $rating)
    {
        if (! $rating) {
            return $query;
        }

        return $query->where('rating_avg', '>=', $rating);
    }

    public function scopeHotelType($query, ?string $type)
    {
        if (! $type) {
            return $query;
        }

        return $query->where('hotel_type', $type);
    }

    /**
     * Hotel dianggap cocok kalau punya SEMUA amenity id yang diminta.
     */
    public function scopeWithAmenityIds($query, array $amenityIds)
    {
        foreach (array_filter($amenityIds) as $amenityId) {
            $query->whereHas('amenities', fn ($q) => $q->where('amenities.id', $amenityId));
        }

        return $query;
    }

    public function scopeBreakfastIncluded($query, bool $only = true)
    {
        if (! $only) {
            return $query;
        }

        return $query->whereHas('roomTypes', fn ($q) => $q->where('breakfast_included', true));
    }

    public function scopeFreeCancellation($query, bool $only = true)
    {
        if (! $only) {
            return $query;
        }

        return $query->whereHas('roomTypes', fn ($q) => $q->where('free_cancellation', true));
    }

    /**
     * Hotel dianggap cocok kalau punya minimal 1 room type dalam rentang harga.
     */
    public function scopePriceBetween($query, ?int $min, ?int $max)
    {
        if (! $min && ! $max) {
            return $query;
        }

        return $query->whereHas('roomTypes', function ($q) use ($min, $max) {
            if ($min) {
                $q->where('base_price', '>=', $min);
            }
            if ($max) {
                $q->where('base_price', '<=', $max);
            }
        });
    }
}
