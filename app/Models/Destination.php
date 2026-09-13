<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'hero_image',
        'things_to_do',
        'travel_information',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'things_to_do' => 'array',
        'travel_information' => 'array',
    ];

    /**
     * Route model binding pakai slug, bukan id.
     * Mendukung route /destinations/{slug} (PRD section 11).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(DestinationImage::class)->orderBy('sort_order');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
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
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%");
        });
    }
}
