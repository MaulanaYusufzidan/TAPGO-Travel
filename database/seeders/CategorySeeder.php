<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Kategori trip untuk filter di halaman /trips.
     */
    protected array $categories = [
        'Adventure',
        'Culture & Heritage',
        'Beach & Island',
        'Nature & Hiking',
        'Culinary',
    ];

    public function run(): void
    {
        foreach ($this->categories as $name) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
