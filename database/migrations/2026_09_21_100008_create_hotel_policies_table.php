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
        Schema::create('hotel_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')
                ->constrained('hotels')
                ->cascadeOnDelete();

            $table->text('check_in_policy')->nullable();
            $table->text('check_out_policy')->nullable();
            $table->text('child_policy')->nullable();
            $table->text('pet_policy')->nullable();
            $table->text('smoking_policy')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('payment_policy')->nullable();

            $table->timestamps();

            // 1 hotel cuma boleh punya 1 baris kebijakan (hasOne).
            $table->unique('hotel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_policies');
    }
};
