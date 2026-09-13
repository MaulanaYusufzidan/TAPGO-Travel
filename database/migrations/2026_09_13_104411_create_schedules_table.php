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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->date('date');
            $table->time('departure_time')->nullable();
            $table->time('return_time')->nullable();
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('booked_seats')->default(0);
            $table->decimal('price', 12, 2);
            $table->enum('status', ['available', 'full', 'closed', 'cancelled'])->default('available');
            $table->timestamps();

            $table->index(['trip_id', 'date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
