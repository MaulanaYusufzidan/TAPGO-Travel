<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'check_in_policy',
        'check_out_policy',
        'child_policy',
        'pet_policy',
        'smoking_policy',
        'cancellation_policy',
        'payment_policy',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
