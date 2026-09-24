<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departure_flight_id')->constrained('flights')->cascadeOnDelete();
            $table->foreignId('return_flight_id')->nullable()->constrained('flights')->cascadeOnDelete();

            $table->decimal('base_price', 12, 2); // harga per penumpang
            $table->unsignedTinyInteger('discount_percentage')->nullable();
            $table->boolean('refundable')->default(false);
            $table->unsignedInteger('seats_available')->default(9);

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_offers');
    }
};
