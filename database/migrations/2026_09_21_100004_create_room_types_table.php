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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')
                ->constrained('hotels')
                ->cascadeOnDelete();

            $table->string('name'); // ex: "Deluxe Room", "Suite"
            $table->string('slug'); // unik per hotel, bukan global

            $table->text('description')->nullable();

            $table->unsignedTinyInteger('max_guests')->default(2);
            $table->string('bed_type')->nullable(); // ex: "1 King Bed", "2 Single Beds"

            $table->unsignedInteger('size_sqm')->nullable(); // luas kamar, m²
            $table->unsignedInteger('quantity')->default(1); // total unit kamar tipe ini

            $table->decimal('base_price', 12, 2);

            $table->boolean('breakfast_included')->default(false);
            $table->boolean('free_cancellation')->default(false);

            $table->enum('status', ['draft', 'published'])->default('draft');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['hotel_id', 'slug']);
            $table->index(['hotel_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
