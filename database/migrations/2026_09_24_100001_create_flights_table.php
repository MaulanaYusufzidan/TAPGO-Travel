<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1 baris = 1 leg penerbangan (berangkat ATAU pulang). Paket round-trip
     * yang dijual & dicari user ada di tabel flight_offers, yang mereferensi
     * 1-2 baris flights (departure + optional return).
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();

            $table->string('flight_number');

            $table->string('origin_code', 5);
            $table->string('origin_city');
            $table->string('destination_code', 5);
            $table->string('destination_city');

            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->unsignedInteger('duration_minutes');
            $table->unsignedTinyInteger('stops')->default(0); // 0 = non-stop

            $table->enum('travel_class', ['Economy', 'Business', 'First'])->default('Economy');

            $table->boolean('wifi')->default(false);
            $table->boolean('meal')->default(false);
            $table->unsignedInteger('baggage_kg')->default(20);

            $table->timestamps();

            $table->index(['origin_code', 'destination_code', 'departure_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
