<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * PDF referensi GeoTrip nunjukin tiap kamar punya beberapa "Your Choice"
     * (ex: "Room Only" non-refundable vs "Breakfast Included" refundable
     * dengan harga beda). Sebelumnya RoomType cuma punya 1 base_price —
     * sekarang base_price tetap ada sebagai harga dasar, dan rate plan ini
     * yang jadi pilihan aktual yang di-booking user.
     */
    public function up(): void
    {
        Schema::create('room_rate_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')
                ->constrained('room_types')
                ->cascadeOnDelete();

            $table->string('name'); // ex: "Room Only", "Breakfast Included"
            $table->decimal('price_addon', 12, 2)->default(0); // ditambahkan ke base_price (bisa 0)

            $table->boolean('breakfast_included')->default(false);
            $table->boolean('free_cancellation')->default(false);
            $table->boolean('refundable')->default(false);

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['room_type_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_rate_plans');
    }
};
