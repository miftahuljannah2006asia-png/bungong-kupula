<?php

namespace App\Services;

use App\Models\Recipe;
use Throwable;

/**
 * Membaca konten statis situs dari resources/content/*.php.
 * Untuk mengubah resep, wilayah, atau hikayat cukup sunting file-file tersebut.
 */
class ContentRepository
{
    /** @var array<string, array> */
    protected array $memo = [];

    protected function load(string $name): array
    {
        return $this->memo[$name] ??= require resource_path("content/{$name}.php");
    }

    /** Resep bawaan dari berkas (tanpa database). @return array<string, array> */
    public function fileRecipes(): array
    {
        return array_column($this->load('recipes'), null, 'slug');
    }

    /**
     * Semua resep yang tampil di situs, kunci = slug.
     * Resep bawaan + resep dari database (admin). Baris database dengan slug yang sama
     * menimpa resep bawaan; bila statusnya disembunyikan, resep tersebut hilang dari situs.
     *
     * @return array<string, array>
     */
    public function recipes(): array
    {
        if (isset($this->memo['recipes.merged'])) {
            return $this->memo['recipes.merged'];
        }

        $recipes = array_map(fn (array $r) => $this->withImageUrl($r), $this->fileRecipes());

        try {
            foreach (Recipe::orderBy('sort_order')->orderBy('id')->get() as $row) {
                if ($row->is_published) {
                    $recipes[$row->slug] = $this->withImageUrl($row->toContent());
                } else {
                    unset($recipes[$row->slug]);
                }
            }
        } catch (Throwable) {
            // Tabel belum dimigrasi: pakai resep bawaan saja.
        }

        return $this->memo['recipes.merged'] = $recipes;
    }

    protected function withImageUrl(array $r): array
    {
        $image = (string) ($r['image'] ?? '');
        $r['image_url'] = str_starts_with($image, 'uploads/') ? asset($image) : asset('images/'.($image ?: 'dapur-pusaka.jpg'));

        return $r;
    }

    public function recipe(string $slug): ?array
    {
        return $this->recipes()[$slug] ?? null;
    }

    /** @return array<string, array> */
    public function districts(): array
    {
        return array_column($this->load('districts'), null, 'slug');
    }

    /** @return array<string, array> */
    public function stories(): array
    {
        return array_column($this->load('stories'), null, 'slug');
    }

    public function story(string $slug): ?array
    {
        return $this->stories()[$slug] ?? null;
    }

    /** Produk contoh (cadangan dan bahan seeder). */
    public function fallbackProducts(): array
    {
        return $this->load('products');
    }

    /** Ringkasan resep untuk laci "Resep Disimpan" (dikirim ke JavaScript). */
    public function recipeIndex(): array
    {
        $index = [];

        foreach ($this->recipes() as $slug => $r) {
            $index[$slug] = [
                'slug' => $slug,
                'name' => $r['name'],
                'category' => $r['category'],
                'region' => $r['region'],
                'image' => $r['image_url'],
                'url' => route('recipes.show', $slug),
            ];
        }

        return $index;
    }
}
