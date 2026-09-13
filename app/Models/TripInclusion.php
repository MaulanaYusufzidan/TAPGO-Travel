<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripInclusion extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'item', 'sort_order'];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
