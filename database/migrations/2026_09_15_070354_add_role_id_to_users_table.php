<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migrasi dari kolom users.role (enum string) ke relasi
     * users.role_id -> roles (PRD section 28 Database Requirements
     * menyebut tabel roles & permissions terpisah).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('role')->constrained('roles')->nullOnDelete();
        });

        // Seed 3 role tetap sesuai PRD section 30 (Customer/Operator/Admin)
        // supaya backfill role_id di bawah ini punya target yang valid,
        // tanpa bergantung urutan jalannya seeder terhadap migration ini.
        foreach ([
            ['name' => 'Customer', 'slug' => 'customer'],
            ['name' => 'Operator', 'slug' => 'operator'],
            ['name' => 'Admin', 'slug' => 'admin'],
        ] as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                array_merge($role, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Pindahkan data role lama (string) ke role_id (FK), berdasarkan slug yang cocok.
        $roles = DB::table('roles')->pluck('id', 'slug');
        foreach ($roles as $slug => $id) {
            DB::table('users')->where('role', $slug)->update(['role_id' => $id]);
        }

        // User yang belum punya role_id (mis. role lama null/tidak dikenal) -> default customer.
        if ($customerId = $roles['customer'] ?? null) {
            DB::table('users')->whereNull('role_id')->update(['role_id' => $customerId]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'operator', 'admin'])->default('customer')->after('email');
        });

        $roles = DB::table('roles')->pluck('slug', 'id');
        foreach ($roles as $id => $slug) {
            DB::table('users')->where('role_id', $id)->update(['role' => $slug]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
