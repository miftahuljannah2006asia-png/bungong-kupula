@extends('layouts.admin')

@php
  $isEdit = $product !== null;
  $v = fn (string $key, $default = '') => old($key, $product?->{$key} ?? $default);
  $features = old('features', $product ? implode("\n", $product->features ?? []) : '');
  $err = fn (string $key) => $errors->first($key);
@endphp

@section('title', $isEdit ? 'Ubah produk' : 'Tambah produk')

@section('content')
<a href="{{ route('admin.products.index') }}" class="text-sm text-ivory-300 hover:text-gold-300">&larr; Daftar produk</a>
<h1 class="mt-4 text-4xl font-semibold">{{ $isEdit ? 'Ubah: '.$product->title : 'Tambah produk baru' }}</h1>

@if ($errors->any())
  <div role="alert" class="mt-6 rounded-xl border border-songket-400/40 bg-songket-500/10 px-5 py-4 text-sm text-ivory-50">
    Ada isian yang perlu diperbaiki. Periksa pesan merah di bawah kolom.
  </div>
@endif

<form method="POST" enctype="multipart/form-data" class="mt-8 space-y-10"
      action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}">
  @csrf
  @if ($isEdit) @method('PUT') @endif

  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Identitas produk</h2>

    <div>
      <label for="title" class="mb-1.5 block text-sm font-semibold">Nama produk</label>
      <input id="title" name="title" type="text" class="field" value="{{ $v('title') }}" required>
      <p class="mt-1 text-xs text-songket-400">{{ $err('title') }}</p>
    </div>

    @unless ($isEdit)
      <div>
        <label for="slug" class="mb-1.5 block text-sm font-semibold">Slug <span class="font-normal text-ivory-500">(kode unik produk; kosongkan agar dibuat otomatis)</span></label>
        <input id="slug" name="slug" type="text" class="field" value="{{ old('slug') }}" placeholder="emping-beureunuen">
        <p class="mt-1 text-xs text-songket-400">{{ $err('slug') }}</p>
      </div>
    @else
      <p class="text-sm text-ivory-500">Kode produk: <span class="text-ivory-300">{{ $product->slug }}</span> (tidak diubah agar pesanan lama tetap cocok)</p>
    @endunless

    <div class="grid gap-5 sm:grid-cols-3">
      <div>
        <label for="artisan" class="mb-1.5 block text-sm font-semibold">Perajin</label>
        <input id="artisan" name="artisan" type="text" class="field" value="{{ $v('artisan') }}" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('artisan') }}</p>
      </div>
      <div>
        <label for="village" class="mb-1.5 block text-sm font-semibold">Gampong</label>
        <input id="village" name="village" type="text" class="field" value="{{ $v('village') }}" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('village') }}</p>
      </div>
      <div>
        <label for="district" class="mb-1.5 block text-sm font-semibold">Kecamatan</label>
        <input id="district" name="district" type="text" class="field" value="{{ $v('district') }}" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('district') }}</p>
      </div>
    </div>

    <div>
      <label for="description" class="mb-1.5 block text-sm font-semibold">Deskripsi</label>
      <textarea id="description" name="description" rows="4" class="field" required>{{ $v('description') }}</textarea>
      <p class="mt-1 text-xs text-songket-400">{{ $err('description') }}</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label for="badge" class="mb-1.5 block text-sm font-semibold">Label <span class="font-normal text-ivory-500">(opsional)</span></label>
        <input id="badge" name="badge" type="text" class="field" value="{{ $v('badge') }}">
        <p class="mt-1 text-xs text-songket-400">{{ $err('badge') }}</p>
      </div>
      <div>
        <label for="unit" class="mb-1.5 block text-sm font-semibold">Satuan / kemasan</label>
        <input id="unit" name="unit" type="text" class="field" value="{{ $v('unit') }}" placeholder="1 Kg (Besek Bambu)" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('unit') }}</p>
      </div>
    </div>

    <div>
      <label for="features" class="mb-1.5 block text-sm font-semibold">Keunggulan <span class="font-normal text-ivory-500">(satu baris satu poin)</span></label>
      <textarea id="features" name="features" rows="4" class="field">{{ $features }}</textarea>
      <p class="mt-1 text-xs text-songket-400">{{ $err('features') }}</p>
    </div>
  </section>

  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Harga &amp; ulasan</h2>
    <div class="grid gap-5 sm:grid-cols-3">
      <div>
        <label for="price" class="mb-1.5 block text-sm font-semibold">Harga (Rp)</label>
        <input id="price" name="price" type="number" min="1" class="field" value="{{ $v('price') }}" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('price') }}</p>
      </div>
      <div>
        <label for="rating" class="mb-1.5 block text-sm font-semibold">Rating (0&ndash;5)</label>
        <input id="rating" name="rating" type="number" step="0.1" min="0" max="5" class="field" value="{{ $v('rating', 5) }}" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('rating') }}</p>
      </div>
      <div>
        <label for="reviews" class="mb-1.5 block text-sm font-semibold">Jumlah ulasan</label>
        <input id="reviews" name="reviews" type="number" min="0" class="field" value="{{ $v('reviews', 0) }}" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('reviews') }}</p>
      </div>
    </div>
  </section>

  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Foto</h2>
    @if ($isEdit && $product->image)
      <p class="text-sm text-ivory-500">Foto saat ini: <span class="text-ivory-300">{{ $product->image }}</span>. Unggah foto baru hanya jika ingin menggantinya.</p>
    @endif
    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label for="image" class="mb-1.5 block text-sm font-semibold">Unggah foto <span class="font-normal text-ivory-500">(JPG/PNG/WebP, maks 4 MB; opsional)</span></label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="field">
        <p class="mt-1 text-xs text-songket-400">{{ $err('image') }}</p>
      </div>
      <div>
        <label for="image_position" class="mb-1.5 block text-sm font-semibold">Posisi foto</label>
        <input id="image_position" name="image_position" type="text" class="field" value="{{ $v('image_position', 'center') }}" placeholder="center">
        <p class="mt-1 text-xs text-songket-400">{{ $err('image_position') }}</p>
      </div>
    </div>
  </section>

  <section class="flex flex-wrap items-center justify-between gap-6 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <div class="flex flex-wrap items-center gap-8">
      <label class="inline-flex items-center gap-3 text-sm font-semibold">
        <input type="checkbox" name="is_active" value="1" class="h-5 w-5 accent-[#e6b94f]" @checked(old('is_active', $product?->is_active ?? true))>
        Tayangkan di situs
      </label>
      <div class="flex items-center gap-3">
        <label for="sort_order" class="text-sm font-semibold">Urutan</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="field !w-24" value="{{ $v('sort_order', 0) }}">
      </div>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('admin.products.index') }}" class="btn btn-line">Batal</a>
      <button type="submit" class="btn btn-gold">{{ $isEdit ? 'Simpan perubahan' : 'Tambahkan produk' }}</button>
    </div>
  </section>
</form>
@endsection
