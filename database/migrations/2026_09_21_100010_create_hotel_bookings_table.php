<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Sengaja dibuat terpisah dari `bookings`/`payments` (sistem Trip) supaya
     * tidak bentrok dengan kolom schedule_id yang wajib di sana — lihat catatan
     * keputusan arsitektur hotel booking.
     */
    public function up(): void
    {
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('hotel_id')->constrained('hotels')->restrictOnDelete();

            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('guests');
            $table->unsignedInteger('rooms');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->enum('status', [
                'pending',
                'awaiting_payment',
                'paid',
                'confirmed',
                'cancelled',
                'completed',
                'expired',
            ])->default('pending');

            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->text('special_request')->nullable();

            $table->timestamps();

            $table->index('booking_code');
            $table->index(['user_id', 'status']);
            $table->index(['hotel_id', 'status']);
            $table->index(['check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
