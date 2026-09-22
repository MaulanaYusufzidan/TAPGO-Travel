<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'name',
        'slug',
        'description',
        'max_guests',
        'bed_type',
        'size_sqm',
        'quantity',
        'base_price',
        'breakfast_included',
        'free_cancellation',
        'status',
    ];

    protected $casts = [
        'max_guests' => 'integer',
        'size_sqm' => 'integer',
        'quantity' => 'integer',
        'base_price' => 'decimal:2',
        'breakfast_included' => 'boolean',
        'free_cancellation' => 'boolean',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(RoomInventory::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity');
    }

    /**
     * Ambil (atau bikin fallback dari base_price) baris inventory untuk 1 tanggal.
     * Dipakai saat cek availability & hitung harga (spec section 33-34).
     */
    public function inventoryForDate(string $date): ?RoomInventory
    {
        return $this->inventory()->where('date', $date)->first();
    }
}
