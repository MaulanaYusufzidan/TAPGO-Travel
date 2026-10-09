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
        Schema::create('nearby_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')
                ->constrained('hotels')
                ->cascadeOnDelete();

            $table->string('name'); // ex: "Ngurah Rai International Airport"
            $table->string('category'); // Airport, Restaurant, Cafe, Shopping, Attraction, Transportation
            $table->decimal('distance', 8, 2); // ex: 4.2
            $table->string('unit', 10)->default('km'); // km, m
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['hotel_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nearby_places');
    }
};
