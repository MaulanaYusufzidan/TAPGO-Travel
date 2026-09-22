<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_booking_id')
                ->constrained('hotel_bookings')
                ->cascadeOnDelete();
            $table->foreignId('room_type_id')
                ->constrained('room_types')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');
            $table->decimal('price_per_night', 12, 2);
            $table->unsignedInteger('nights');
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            $table->index(['hotel_booking_id', 'room_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_booking_rooms');
    }
};
