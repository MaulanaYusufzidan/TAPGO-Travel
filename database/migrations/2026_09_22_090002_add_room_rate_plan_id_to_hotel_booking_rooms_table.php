<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_booking_rooms', function (Blueprint $table) {
            $table->foreignId('room_rate_plan_id')
                ->nullable()
                ->after('room_type_id')
                ->constrained('room_rate_plans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hotel_booking_rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('room_rate_plan_id');
        });
    }
};
