<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_booking_id')->constrained('flight_bookings')->cascadeOnDelete();

            $table->string('payment_code')->unique();
            $table->string('method')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('transaction_reference')->nullable();

            $table->timestamps();

            $table->index('flight_booking_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_payments');
    }
};
