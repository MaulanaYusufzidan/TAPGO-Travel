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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Wajib diisi di level aplikasi: user cuma boleh review kalau
            // hotel_booking-nya sudah 'completed' (spec section 13).
            $table->foreignId('hotel_booking_id')
                ->nullable()
                ->constrained('hotel_bookings')
                ->nullOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->unsignedTinyInteger('cleanliness_rating')->nullable();
            $table->unsignedTinyInteger('location_rating')->nullable();
            $table->unsignedTinyInteger('service_rating')->nullable();
            $table->unsignedTinyInteger('value_rating')->nullable();

            $table->string('title')->nullable();
            $table->text('comment');

            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');

            $table->timestamps();

            // 1 booking cuma bisa dipakai untuk 1 review.
            $table->unique('hotel_booking_id');
            $table->index(['hotel_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
