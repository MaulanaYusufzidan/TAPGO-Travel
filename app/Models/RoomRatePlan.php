<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomRatePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'name',
        'price_addon',
        'breakfast_included',
        'free_cancellation',
        'refundable',
        'sort_order',
    ];

    protected $casts = [
        'price_addon' => 'decimal:2',
        'breakfast_included' => 'boolean',
        'free_cancellation' => 'boolean',
        'refundable' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Harga akhir rate plan ini = harga dasar room type + tambahan rate plan.
     */
    public function getFinalPriceAttribute(): float
    {
        return (float) $this->roomType->base_price + (float) $this->price_addon;
    }
}
