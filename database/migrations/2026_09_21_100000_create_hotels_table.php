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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            $table->string('hotel_type')->nullable(); // ex: "Resort", "Boutique Hotel", "Villa"
            $table->unsignedTinyInteger('star_rating')->nullable();

            $table->string('address')->nullable();
            $table->string('city');
            $table->string('province')->nullable();
            $table->string('country')->default('Indonesia');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Cached rating, sama pola dengan Trip::rating_avg / reviews_count.
            // Dihitung ulang dari tabel reviews (lihat spec section 42), bukan diedit manual.
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);

            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('draft');

            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index(['city', 'status']);
            $table->index(['status', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
