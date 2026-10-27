<?php

namespace App\Services;

use App\Models\Recipe;

/** Menyalin resep bawaan (resources/content/recipes.php) ke database agar bisa diubah lewat admin. */
class RecipeImporter
{
    public function __construct(protected ContentRepository $content) {}

    /** @return int jumlah resep yang diimpor */
    public function import(): int
    {
        $existing = Recipe::pluck('slug')->all();
        $count = 0;

        foreach ($this->content->fileRecipes() as $slug => $r) {
            if (in_array($slug, $existing, true)) {
                continue;
            }

            Recipe::create([
                'slug' => $slug,
                'name' => $r['name'],
                'short_name' => $r['short_name'] ?? null,
                'category' => $r['category'],
                'region' => $r['region'],
                'short_desc' => $r['short_desc'],
                'full_story' => $r['full_story'],
                'prep_time' => $r['prep_time'],
                'cook_time' => $r['cook_time'],
                'servings' => $r['servings'],
                'difficulty' => $r['difficulty'],
                'main_ingredients' => $r['main_ingredients'],
                'spice_blend' => $r['spice_blend'],
                'steps' => $r['steps'],
                'chef_tips' => $r['chef_tips'],
                'cultural_note' => $r['cultural_note'],
                'spicy' => $r['flavor_profile']['spicy'],
                'savory' => $r['flavor_profile']['savory'],
                'sweet' => $r['flavor_profile']['sweet'],
                'aromatic' => $r['flavor_profile']['aromatic'],
                'image' => $r['image'],
                'image_position' => $r['image_position'] ?? 'center',
                'is_published' => true,
                'sort_order' => 0,
            ]);

            $count++;
        }

        return $count;
    }

    /** Jumlah resep bawaan yang belum ada di database. */
    public function pending(): int
    {
        $existing = Recipe::pluck('slug')->all();

        return count(array_diff(array_keys($this->content->fileRecipes()), $existing));
    }
}
