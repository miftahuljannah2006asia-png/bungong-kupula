<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Katalog produk oleh-oleh, dibaca dari tabel `products` di MySQL.
 * Bila tabel belum dimigrasi atau masih kosong, dipakai produk contoh
 * dari resources/content/products.php agar situs tidak error.
 */
class ProductCatalog
{
    /** @var array<int, array>|null */
    protected ?array $memo = null;

    public function __construct(protected ContentRepository $content) {}

    /** @return array<int, array> */
    public function all(): array
    {
        if ($this->memo !== null) {
            return $this->memo;
        }

        try {
            $rows = Product::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

            if ($rows->isNotEmpty()) {
                return $this->memo = $rows->map(fn (Product $p) => $this->normalise($p->toCatalog()))->all();
            }
        } catch (Throwable $e) {
            Log::warning('Gagal membaca produk dari database: '.$e->getMessage());
        }

        return $this->memo = array_map(fn (array $p) => $this->normalise($p), $this->content->fallbackProducts());
    }

    /** Menyeragamkan struktur produk dan menambahkan image_url. */
    protected function normalise(array $p): array
    {
        $image = (string) ($p['image'] ?? '');

        $p['image_url'] = match (true) {
            $image === '' => asset('images/dapur-pusaka.jpg'),
            str_starts_with($image, 'http') => $image,
            str_starts_with($image, 'uploads/') => asset($image),
            default => asset('images/'.ltrim($image, '/')),
        };

        $p['image_position'] = $p['image_position'] ?? 'center';
        $p['price'] = (int) $p['price'];
        $p['rating'] = (float) ($p['rating'] ?? 0);
        $p['reviews'] = (int) ($p['reviews'] ?? 0);
        $p['features'] = $p['features'] ?? [];

        return $p;
    }
}
