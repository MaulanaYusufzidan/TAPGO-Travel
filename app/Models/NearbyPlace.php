<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NearbyPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'category',
        'distance',
        'unit',
        'description',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
