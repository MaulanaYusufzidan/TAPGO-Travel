<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'destination_id',
        'category_id',
        'title',
        'slug',
        'description',
        'meeting_point',
        'duration',
        'min_group_size',
        'max_group_size',
        'base_price',
        'rating_avg',
        'reviews_count',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TripImage::class)->orderBy('sort_order');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(TripItinerary::class)->orderBy('day_number');
    }

    public function inclusions(): HasMany
    {
        return $this->hasMany(TripInclusion::class)->orderBy('sort_order');
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(TripExclusion::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
