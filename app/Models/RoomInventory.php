<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomInventory extends Model
{
    use HasFactory;

    protected $table = 'room_inventory';

    protected $fillable = [
        'room_type_id',
        'date',
        'available_rooms',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'available_rooms' => 'integer',
        'price' => 'decimal:2',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
