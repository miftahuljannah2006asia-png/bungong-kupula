<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'products' => Product::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', ['product' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);

        $slug = Str::slug($data['slug'] ?: $data['title']);
        $this->assertSlugAvailable($slug, null);

        $product = Product::create($this->attributes($data) + [
            'slug' => $slug,
            'image' => $request->hasFile('image') ? $this->storeImage($request->file('image')) : null,
        ]);

        return redirect()->route('admin.products.index')
            ->with('status', "Produk “{$product->title}” berhasil ditambahkan.");
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);
        $attributes = $this->attributes($data);

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $attributes['image'] = $this->storeImage($request->file('image'));
        }

        $product->update($attributes);

        return redirect()->route('admin.products.index')
            ->with('status', "Perubahan pada “{$product->title}” tersimpan.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteImage($product->image);
        $title = $product->title;
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', "“{$title}” dihapus.");
    }

    /* ------------------------------------------------------------------ */

    protected function validated(Request $request, ?Product $product): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'artisan' => ['required', 'string', 'max:191'],
            'village' => ['required', 'string', 'max:191'],
            'district' => ['required', 'string', 'max:191'],
            'price' => ['required', 'integer', 'between:1,100000000'],
            'unit' => ['required', 'string', 'max:191'],
            'rating' => ['required', 'numeric', 'between:0,5'],
            'reviews' => ['required', 'integer', 'between:0,1000000'],
            'description' => ['required', 'string', 'max:2000'],
            'badge' => ['nullable', 'string', 'max:191'],
            'features' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_position' => ['nullable', 'string', 'max:30', 'regex:/^[a-zA-Z0-9% ]+$/'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'between:0,9999'],
        ], [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute terlalu panjang.',
            'between' => ':attribute harus antara :min dan :max.',
            'integer' => ':attribute harus berupa angka bulat.',
            'numeric' => ':attribute harus berupa angka.',
            'image' => 'Berkas harus berupa gambar.',
            'mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 4 MB.',
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ], [
            'title' => 'Nama produk', 'slug' => 'Slug', 'artisan' => 'Perajin', 'village' => 'Gampong',
            'district' => 'Kecamatan', 'price' => 'Harga', 'unit' => 'Satuan', 'rating' => 'Rating',
            'reviews' => 'Jumlah ulasan', 'description' => 'Deskripsi', 'badge' => 'Label',
            'features' => 'Keunggulan', 'image' => 'Foto', 'image_position' => 'Posisi foto',
            'sort_order' => 'Urutan',
        ]);
    }

    /** Mengubah isian formulir menjadi kolom tabel. */
    protected function attributes(array $data): array
    {
        $features = preg_split('/\R/', (string) ($data['features'] ?? '')) ?: [];

        return [
            'title' => trim($data['title']),
            'artisan' => trim($data['artisan']),
            'village' => trim($data['village']),
            'district' => trim($data['district']),
            'price' => (int) $data['price'],
            'unit' => trim($data['unit']),
            'rating' => round((float) $data['rating'], 1),
            'reviews' => (int) $data['reviews'],
            'description' => trim($data['description']),
            'badge' => filled($data['badge'] ?? null) ? trim($data['badge']) : null,
            'features' => array_values(array_filter(array_map('trim', $features), fn ($l) => $l !== '')),
            'image_position' => filled($data['image_position'] ?? null) ? trim($data['image_position']) : 'center',
            'is_active' => (bool) ($data['is_active'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    protected function assertSlugAvailable(string $slug, ?Product $ignore): void
    {
        $taken = Product::where('slug', $slug)
            ->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))
            ->exists();

        if ($slug === '' || $taken) {
            throw ValidationException::withMessages([
                'slug' => 'Slug sudah dipakai produk lain. Ubah nama atau isi slug yang berbeda.',
            ]);
        }
    }

    protected function storeImage(UploadedFile $file): string
    {
        $dir = public_path('uploads/products');

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $name = time().'-'.Str::lower(Str::random(10)).'.'.($file->guessExtension() ?: 'jpg');
        $file->move($dir, $name);

        return 'uploads/products/'.$name;
    }

    protected function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/')) {
            @unlink(public_path($path));
        }
    }
}
