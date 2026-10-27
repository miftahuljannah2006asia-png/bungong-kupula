<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request, ProductCatalog $catalog): JsonResponse
    {
        // Kolom "website" disembunyikan dari manusia; bot biasanya mengisinya.
        if (filled($request->input('website'))) {
            return response()->json(['ok' => true, 'code' => 'BK-DITERIMA', 'total' => 0, 'whatsapp_url' => null]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.id' => ['required', 'string', 'max:120'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:50'],
        ], [
            'required' => ':attribute wajib diisi.',
            'min' => ':attribute terlalu pendek.',
            'max' => ':attribute terlalu panjang.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, +, - dan tanda kurung.',
            'items.required' => 'Keranjang masih kosong.',
            'items.min' => 'Keranjang masih kosong.',
        ], [
            'name' => 'Nama penerima',
            'phone' => 'Nomor WhatsApp',
            'city' => 'Kota tujuan',
            'address' => 'Alamat lengkap',
            'notes' => 'Catatan',
        ]);

        // Harga selalu dihitung ulang di server dari katalog; angka dari browser tidak dipercaya.
        $products = collect($catalog->all())->keyBy('id');
        $lines = [];

        foreach ($data['items'] as $item) {
            $product = $products->get($item['id']);

            if ($product === null) {
                throw ValidationException::withMessages([
                    'items' => 'Ada produk di keranjang yang sudah tidak tersedia. Muat ulang halaman, lalu pilih kembali.',
                ]);
            }

            $id = $product['id'];
            $lines[$id] ??= ['product' => $product, 'qty' => 0];
            $lines[$id]['qty'] = min(50, $lines[$id]['qty'] + (int) $item['qty']);
        }

        $total = array_sum(array_map(fn ($l) => $l['product']['price'] * $l['qty'], $lines));

        $order = DB::transaction(function () use ($data, $lines, $total, $request) {
            $order = Order::create([
                'code' => $this->makeCode(),
                'name' => trim($data['name']),
                'phone' => $this->normalisePhone($data['phone']),
                'city' => trim($data['city']),
                'address' => trim($data['address']),
                'notes' => filled($data['notes'] ?? null) ? trim($data['notes']) : null,
                'total' => $total,
                'status' => 'baru',
                'ip' => $request->ip(),
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']['id'],
                    'title' => $line['product']['title'],
                    'unit' => $line['product']['unit'] ?? null,
                    'price' => $line['product']['price'],
                    'qty' => $line['qty'],
                ]);
            }

            return $order;
        });

        return response()->json([
            'ok' => true,
            'code' => $order->code,
            'total' => $order->total,
            'whatsapp_url' => $this->whatsappUrl($order),
        ], 201);
    }

    /** Contoh: BK-261010-7H3QK (tanpa huruf/angka yang mudah tertukar). */
    protected function makeCode(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $suffix = '';
            for ($i = 0; $i < 5; $i++) {
                $suffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $code = 'BK-'.now()->format('ymd').'-'.$suffix;
        } while (Order::where('code', $code)->exists());

        return $code;
    }

    /** 0812-xxx → 62812xxx. */
    protected function normalisePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        return $digits;
    }

    protected function whatsappUrl(Order $order): string
    {
        $lines = $order->items->map(fn ($i) => "- {$i->qty}x {$i->title}")->implode("\n");

        $text = "Halo Bungong Kupula, saya {$order->name}.\n"
            ."Kode pesanan: {$order->code}\n\n"
            ."{$lines}\n\n"
            .'Total: Rp '.number_format($order->total, 0, ',', '.')." (belum ongkir)\n"
            .'Mohon info ongkos kirim dan cara pembayaran. Terima kasih.';

        return 'https://wa.me/'.preg_replace('/\D/', '', (string) config('kupula.whatsapp')).'?text='.rawurlencode($text);
    }
}
