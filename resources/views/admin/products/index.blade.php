@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="text-4xl font-semibold">Produk oleh-oleh</h1>
    <p class="mt-2 text-ivory-300">Atur harga, rating, dan tampilan produk di Pasar Oleh-oleh.</p>
  </div>
  <a href="{{ route('admin.products.create') }}" class="btn btn-gold">+ Tambah produk</a>
</div>

<div class="mt-8 overflow-x-auto rounded-2xl border border-ivory-100/10 bg-ink-900">
  <table class="w-full text-left text-sm">
    <thead class="border-b border-ivory-100/10 text-ivory-500">
      <tr>
        <th class="px-5 py-3 font-medium">Produk</th>
        <th class="px-5 py-3 font-medium">Harga</th>
        <th class="px-5 py-3 font-medium">Status</th>
        <th class="px-5 py-3 text-right font-medium">Tindakan</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ivory-100/10">
      @forelse ($products as $p)
        @php
          $img = $p->image
            ? (str_starts_with($p->image, 'http') ? $p->image : asset(str_starts_with($p->image, 'uploads/') ? $p->image : 'images/'.$p->image))
            : asset('images/dapur-pusaka.jpg');
        @endphp
        <tr>
          <td class="px-5 py-4">
            <div class="flex items-center gap-4">
              <img src="{{ $img }}" alt="" class="h-14 w-14 shrink-0 rounded-xl object-cover" loading="lazy">
              <div>
                <p class="font-semibold text-ivory-50">{{ $p->title }}</p>
                <p class="text-xs text-ivory-500">{{ $p->artisan }} &middot; {{ $p->district }}</p>
              </div>
            </div>
          </td>
          <td class="px-5 py-4">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
          <td class="px-5 py-4">
            @if ($p->is_active)
              <span class="rounded-full bg-leaf-400/15 px-3 py-1 text-xs font-semibold text-leaf-400">Tayang</span>
            @else
              <span class="rounded-full bg-ivory-100/10 px-3 py-1 text-xs font-semibold text-ivory-500">Disembunyikan</span>
            @endif
          </td>
          <td class="px-5 py-4">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.products.edit', $p) }}" class="rounded-lg border border-ivory-100/15 px-3 py-2 font-semibold hover:border-gold-400 hover:text-gold-300">Ubah</a>
              <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Hapus produk ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg px-3 py-2 text-ivory-500 hover:text-songket-400">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="px-5 py-12 text-center text-ivory-500">Belum ada produk. Klik &ldquo;Tambah produk&rdquo; atau jalankan <code>php artisan db:seed</code>.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
