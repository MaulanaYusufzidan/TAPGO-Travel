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
        Schema::create('room_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')
                ->constrained('room_types')
                ->cascadeOnDelete();

            $table->date('date');
            $table->unsignedInteger('available_rooms');
            $table->decimal('price', 12, 2); // harga per malam bisa beda per tanggal

            $table->timestamps();

            // 1 baris per room_type per tanggal — jadi harga & availability
            // bisa dicek/diupdate atomically saat booking (spec section 33-34).
            $table->unique(['room_type_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_inventory');
    }
};
