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
        Schema::create('hotel_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_booking_id')
                ->constrained('hotel_bookings')
                ->cascadeOnDelete();

            $table->string('payment_code')->unique();
            $table->string('method')->nullable(); // bank_transfer, credit_card, e_wallet, qris
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('raw_response')->nullable();

            $table->timestamps();

            $table->index('hotel_booking_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_payments');
    }
};
