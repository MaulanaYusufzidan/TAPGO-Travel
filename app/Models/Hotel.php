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
}
