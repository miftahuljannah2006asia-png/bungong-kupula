<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\ContentRepository;
use Illuminate\Database\Seeder;

/** Mengisi tabel products dengan produk contoh dari resources/content/products.php. */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (app(ContentRepository::class)->fallbackProducts() as $i => $p) {
            // firstOrCreate: produk yang sudah diubah lewat admin tidak ditimpa.
            Product::firstOrCreate(['slug' => $p['id']], [
                'title' => $p['title'],
                'artisan' => $p['artisan'],
                'village' => $p['village'],
                'district' => $p['district'],
                'price' => $p['price'],
                'unit' => $p['unit'],
                'rating' => $p['rating'],
                'reviews' => $p['reviews'],
                'description' => $p['description'],
                'badge' => $p['badge'] ?? null,
                'features' => $p['features'] ?? [],
                'image' => $p['image'] ?? null,
                'image_position' => $p['image_position'] ?? 'center',
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
