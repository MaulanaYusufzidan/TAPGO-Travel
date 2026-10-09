<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flight_offer_id')->constrained('flight_offers')->restrictOnDelete();

            $table->unsignedInteger('passengers');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->enum('status', [
                'pending', 'awaiting_payment', 'paid', 'confirmed', 'cancelled', 'completed', 'expired',
            ])->default('pending');

            $table->string('contact_email');
            $table->string('contact_phone');

            $table->timestamps();

            $table->index('booking_code');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
