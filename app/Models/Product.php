<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'slug', 'title', 'artisan', 'village', 'district',
        'price', 'unit', 'rating', 'reviews', 'description', 'badge',
        'features', 'image', 'image_position', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'rating' => 'float',
            'reviews' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** Bentuk array yang sama dengan resources/content/products.php (id = slug). */
    public function toCatalog(): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'artisan' => $this->artisan,
            'village' => $this->village,
            'district' => $this->district,
            'price' => $this->price,
            'unit' => $this->unit,
            'rating' => $this->rating,
            'reviews' => $this->reviews,
            'description' => $this->description,
            'badge' => (string) $this->badge,
            'features' => $this->features ?? [],
            'image' => (string) $this->image,
            'image_position' => $this->image_position ?: 'center',
        ];
    }
}
