<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public const DIFFICULTIES = ['Mudah', 'Menengah', 'Keahlian Khusus'];

    protected $fillable = [
        'slug', 'name', 'short_name', 'category', 'region', 'short_desc', 'full_story',
        'prep_time', 'cook_time', 'servings', 'difficulty',
        'main_ingredients', 'spice_blend', 'steps', 'chef_tips', 'cultural_note',
        'spicy', 'savory', 'sweet', 'aromatic',
        'image', 'image_position', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'main_ingredients' => 'array',
            'spice_blend' => 'array',
            'steps' => 'array',
            'is_published' => 'boolean',
            'spicy' => 'integer',
            'savory' => 'integer',
            'sweet' => 'integer',
            'aromatic' => 'integer',
        ];
    }

    /** Bentuk yang sama dengan resep di resources/content/recipes.php. */
    public function toContent(): array
    {
        return [
            'slug' => $this->slug,
            'short_name' => $this->short_name ?: $this->name,
            'image' => $this->image ?: 'dapur-pusaka.jpg',
            'image_position' => $this->image_position ?: 'center',
            'name' => $this->name,
            'category' => $this->category,
            'region' => $this->region,
            'short_desc' => $this->short_desc,
            'full_story' => $this->full_story,
            'prep_time' => $this->prep_time,
            'cook_time' => $this->cook_time,
            'servings' => $this->servings,
            'difficulty' => $this->difficulty,
            'main_ingredients' => $this->main_ingredients ?? [],
            'spice_blend' => $this->spice_blend ?? [],
            'steps' => $this->steps ?? [],
            'chef_tips' => $this->chef_tips,
            'cultural_note' => $this->cultural_note,
            'flavor_profile' => [
                'spicy' => $this->spicy,
                'savory' => $this->savory,
                'sweet' => $this->sweet,
                'aromatic' => $this->aromatic,
            ],
        ];
    }
}
