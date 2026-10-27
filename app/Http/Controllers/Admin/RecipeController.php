<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Services\ContentRepository;
use App\Services\RecipeImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RecipeController extends Controller
{
    public function __construct(protected ContentRepository $content) {}

    public function index(RecipeImporter $importer)
    {
        return view('admin.recipes.index', [
            'recipes' => Recipe::orderBy('sort_order')->orderBy('id')->get(),
            'builtin' => array_keys($this->content->fileRecipes()),
            'pendingImport' => $importer->pending(),
        ]);
    }

    public function create()
    {
        return view('admin.recipes.form', [
            'recipe' => null,
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);

        $slug = Str::slug($data['slug'] ?: $data['name']);
        $this->assertSlugAvailable($slug, null);

        $recipe = Recipe::create($this->attributes($data) + [
            'slug' => $slug,
            'image' => $this->storeImage($request->file('image')),
        ]);

        return redirect()->route('admin.recipes.index')
            ->with('status', "Kuliner “{$recipe->name}” berhasil ditambahkan dan sudah tampil di situs.");
    }

    public function edit(Recipe $recipe)
    {
        return view('admin.recipes.form', [
            'recipe' => $recipe,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $data = $this->validated($request, $recipe);
        $attributes = $this->attributes($data);

        if ($request->hasFile('image')) {
            $this->deleteImage($recipe->image);
            $attributes['image'] = $this->storeImage($request->file('image'));
        }

        $recipe->update($attributes);

        return redirect()->route('admin.recipes.index')
            ->with('status', "Perubahan pada “{$recipe->name}” tersimpan.");
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        // Resep bawaan hanya disembunyikan; bila baris dihapus, versi bawaan akan muncul lagi.
        if (array_key_exists($recipe->slug, $this->content->fileRecipes())) {
            $recipe->update(['is_published' => false]);

            return redirect()->route('admin.recipes.index')
                ->with('status', "“{$recipe->name}” adalah resep bawaan, jadi disembunyikan dari situs (tidak dihapus).");
        }

        $this->deleteImage($recipe->image);
        $name = $recipe->name;
        $recipe->delete();

        return redirect()->route('admin.recipes.index')->with('status', "“{$name}” dihapus.");
    }

    public function import(RecipeImporter $importer): RedirectResponse
    {
        $count = $importer->import();

        return redirect()->route('admin.recipes.index')
            ->with('status', $count > 0 ? "{$count} resep bawaan diimpor dan kini bisa diubah." : 'Semua resep bawaan sudah diimpor.');
    }

    /* ------------------------------------------------------------------ */

    protected function validated(Request $request, ?Recipe $recipe): array
    {
        $score = ['required', 'integer', 'between:0,100'];

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'short_name' => ['nullable', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:60'],
            'region' => ['required', 'string', 'max:120'],
            'short_desc' => ['required', 'string', 'max:300'],
            'full_story' => ['required', 'string', 'max:8000'],
            'prep_time' => ['required', 'string', 'max:40'],
            'cook_time' => ['required', 'string', 'max:40'],
            'servings' => ['required', 'string', 'max:60'],
            'difficulty' => ['required', 'in:'.implode(',', Recipe::DIFFICULTIES)],
            'main_ingredients' => ['required', 'string', 'max:4000'],
            'spice_blend' => ['required', 'string', 'max:4000'],
            'steps' => ['required', 'string', 'max:10000'],
            'chef_tips' => ['required', 'string', 'max:2000'],
            'cultural_note' => ['required', 'string', 'max:2000'],
            'spicy' => $score,
            'savory' => $score,
            'sweet' => $score,
            'aromatic' => $score,
            'image' => [$recipe ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_position' => ['nullable', 'string', 'max:30', 'regex:/^[a-zA-Z0-9% ]+$/'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'between:0,9999'],
        ], [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute terlalu panjang.',
            'between' => ':attribute harus antara :min dan :max.',
            'integer' => ':attribute harus berupa angka.',
            'image' => 'Berkas harus berupa gambar.',
            'mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 4 MB.',
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ], [
            'name' => 'Nama kuliner', 'slug' => 'Slug', 'short_name' => 'Nama singkat', 'category' => 'Kategori',
            'region' => 'Asal wilayah', 'short_desc' => 'Deskripsi singkat', 'full_story' => 'Kisah lengkap',
            'prep_time' => 'Waktu persiapan', 'cook_time' => 'Waktu memasak', 'servings' => 'Porsi',
            'difficulty' => 'Tingkat kesulitan', 'main_ingredients' => 'Bahan utama', 'spice_blend' => 'Bumbu dan rempah',
            'steps' => 'Langkah memasak', 'chef_tips' => 'Rahasia dapur', 'cultural_note' => 'Catatan adat',
            'spicy' => 'Pedas', 'savory' => 'Gurih', 'sweet' => 'Manis', 'aromatic' => 'Aroma rempah',
            'image' => 'Foto', 'image_position' => 'Posisi foto',
        ]);
    }

    /** Mengubah isian formulir menjadi kolom tabel. */
    protected function attributes(array $data): array
    {
        return [
            'name' => trim($data['name']),
            'short_name' => filled($data['short_name'] ?? null) ? trim($data['short_name']) : null,
            'category' => trim($data['category']),
            'region' => trim($data['region']),
            'short_desc' => trim($data['short_desc']),
            'full_story' => trim($data['full_story']),
            'prep_time' => trim($data['prep_time']),
            'cook_time' => trim($data['cook_time']),
            'servings' => trim($data['servings']),
            'difficulty' => $data['difficulty'],
            'main_ingredients' => $this->lines($data['main_ingredients']),
            'spice_blend' => $this->lines($data['spice_blend']),
            'steps' => $this->lines($data['steps']),
            'chef_tips' => trim($data['chef_tips']),
            'cultural_note' => trim($data['cultural_note']),
            'spicy' => (int) $data['spicy'],
            'savory' => (int) $data['savory'],
            'sweet' => (int) $data['sweet'],
            'aromatic' => (int) $data['aromatic'],
            'image_position' => filled($data['image_position'] ?? null) ? trim($data['image_position']) : 'center',
            'is_published' => (bool) ($data['is_published'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    /** Satu baris = satu butir. */
    protected function lines(string $text): array
    {
        $lines = preg_split('/\R/', $text) ?: [];

        return array_values(array_filter(array_map('trim', $lines), fn ($l) => $l !== ''));
    }

    protected function assertSlugAvailable(string $slug, ?Recipe $ignore): void
    {
        $taken = Recipe::where('slug', $slug)->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))->exists()
            || array_key_exists($slug, $this->content->fileRecipes());

        if ($slug === '' || $taken) {
            throw ValidationException::withMessages([
                'slug' => 'Slug sudah dipakai kuliner lain. Ubah nama atau isi slug yang berbeda.',
            ]);
        }
    }

    protected function categories(): array
    {
        $all = collect($this->content->recipes())->pluck('category')
            ->merge(Recipe::pluck('category'))
            ->unique()->sort()->values()->all();

        return $all;
    }

    protected function storeImage(UploadedFile $file): string
    {
        $dir = public_path('uploads/recipes');

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $name = time().'-'.Str::lower(Str::random(10)).'.'.($file->guessExtension() ?: 'jpg');
        $file->move($dir, $name);

        return 'uploads/recipes/'.$name;
    }

    protected function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/')) {
            @unlink(public_path($path));
        }
    }
}
