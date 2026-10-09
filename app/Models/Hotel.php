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
        'discount_percentage',
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
        'discount_percentage' => 'integer',
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

    /**
     * Terapkan discount_percentage hotel ke sebuah harga (dipakai di view
     * buat nampilin harga coret, section 25 spec).
     */
    public function priceAfterDiscount(float $price): float
    {
        if (! $this->discount_percentage) {
            return $price;
        }

        return round($price * (1 - $this->discount_percentage / 100), -2);
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
     * PDF referensi GeoTrip: "Star Ratings" (checkbox 5★/4★/3★) terpisah
     * dari "Customer Ratings" (skor review, sudah ada di scopeMinRating).
     */
    public function scopeStarRatings($query, array $stars)
    {
        $stars = array_filter(array_map('intval', $stars));

        if (empty($stars)) {
            return $query;
        }

        return $query->whereIn('star_rating', $stars);
    }

    /**
     * PDF referensi GeoTrip: filter "Bed Type" di sidebar — bed_type
     * disimpan di RoomType, jadi hotel cocok kalau punya minimal 1 room
     * type dengan salah satu bed type yang dipilih.
     */
    public function scopeBedTypes($query, array $bedTypes)
    {
        $bedTypes = array_filter($bedTypes);

        if (empty($bedTypes)) {
            return $query;
        }

        return $query->whereHas('roomTypes', fn ($q) => $q->whereIn('bed_type', $bedTypes));
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
