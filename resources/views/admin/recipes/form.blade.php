@extends('layouts.admin')

@php
  $isEdit = $recipe !== null;
  $v = fn (string $key, $default = '') => old($key, $recipe?->{$key} ?? $default);
  $lines = fn (string $key) => old($key, $recipe ? implode("\n", $recipe->{$key} ?? []) : '');
  $currentImage = $recipe && $recipe->image ? asset(str_starts_with($recipe->image, 'uploads/') ? $recipe->image : 'images/'.$recipe->image) : null;
  $err = fn (string $key) => $errors->first($key);
@endphp

@section('title', $isEdit ? 'Ubah kuliner' : 'Tambah kuliner')

@section('content')
<a href="{{ route('admin.recipes.index') }}" class="text-sm text-ivory-300 hover:text-gold-300">&larr; Daftar kuliner</a>
<h1 class="mt-4 text-4xl font-semibold">{{ $isEdit ? 'Ubah: '.$recipe->name : 'Tambah kuliner baru' }}</h1>

@if ($errors->any())
  <div role="alert" class="mt-6 rounded-xl border border-songket-400/40 bg-songket-500/10 px-5 py-4 text-sm text-ivory-50">
    Ada isian yang perlu diperbaiki. Periksa kolom yang ditandai merah.
  </div>
@endif

<form method="POST" enctype="multipart/form-data" class="mt-8 space-y-10"
      action="{{ $isEdit ? route('admin.recipes.update', $recipe) : route('admin.recipes.store') }}">
  @csrf
  @if ($isEdit) @method('PUT') @endif

  {{-- ========== Identitas ========== --}}
  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Identitas hidangan</h2>

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label for="name" class="mb-1.5 block text-sm font-semibold">Nama kuliner</label>
        <input id="name" name="name" type="text" class="field" value="{{ $v('name') }}" placeholder="Contoh: Kuah Pliek U" required @if ($err('name')) aria-invalid="true" @endif>
        <p class="mt-1 text-xs text-songket-400">{{ $err('name') }}</p>
      </div>
      <div>
        <label for="short_name" class="mb-1.5 block text-sm font-semibold">Nama singkat <span class="font-normal text-ivory-500">(untuk pita di beranda)</span></label>
        <input id="short_name" name="short_name" type="text" class="field" value="{{ $v('short_name') }}" placeholder="Contoh: Kuah pliek">
        <p class="mt-1 text-xs text-songket-400">{{ $err('short_name') }}</p>
      </div>
    </div>

    @unless ($isEdit)
      <div>
        <label for="slug" class="mb-1.5 block text-sm font-semibold">Slug <span class="font-normal text-ivory-500">(alamat halaman; kosongkan agar dibuat otomatis dari nama)</span></label>
        <input id="slug" name="slug" type="text" class="field" value="{{ old('slug') }}" placeholder="kuah-pliek-u">
        <p class="mt-1 text-xs text-songket-400">{{ $err('slug') }}</p>
      </div>
    @else
      <p class="text-sm text-ivory-500">Alamat halaman: <span class="text-ivory-300">/pustaka-rasa/{{ $recipe->slug }}</span> (tidak diubah agar tautan lama tetap berfungsi)</p>
    @endunless

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label for="category" class="mb-1.5 block text-sm font-semibold">Kategori</label>
        <input id="category" name="category" type="text" list="kategori" class="field" value="{{ $v('category') }}" placeholder="Pilih atau ketik kategori baru" required>
        <datalist id="kategori">
          @foreach ($categories as $c) <option value="{{ $c }}"> @endforeach
        </datalist>
        <p class="mt-1 text-xs text-songket-400">{{ $err('category') }}</p>
      </div>
      <div>
        <label for="region" class="mb-1.5 block text-sm font-semibold">Asal wilayah</label>
        <input id="region" name="region" type="text" class="field" value="{{ $v('region') }}" placeholder="Contoh: Kecamatan Tangse" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('region') }}</p>
      </div>
    </div>

    <div>
      <label for="short_desc" class="mb-1.5 block text-sm font-semibold">Deskripsi singkat <span class="font-normal text-ivory-500">(maks. 300 karakter, tampil di kartu)</span></label>
      <textarea id="short_desc" name="short_desc" rows="2" maxlength="300" class="field" required>{{ $v('short_desc') }}</textarea>
      <p class="mt-1 text-xs text-songket-400">{{ $err('short_desc') }}</p>
    </div>

    <div>
      <label for="full_story" class="mb-1.5 block text-sm font-semibold">Kisah di balik hidangan</label>
      <textarea id="full_story" name="full_story" rows="7" class="field" required>{{ $v('full_story') }}</textarea>
      <p class="mt-1 text-xs text-songket-400">{{ $err('full_story') }}</p>
    </div>
  </section>

  {{-- ========== Foto ========== --}}
  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Foto</h2>
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label for="image" class="mb-1.5 block text-sm font-semibold">{{ $isEdit ? 'Ganti foto (opsional)' : 'Unggah foto' }}</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="field file:mr-4 file:rounded-full file:border-0 file:bg-gold-400 file:px-4 file:py-1.5 file:text-sm file:font-semibold file:text-ink-950" @unless ($isEdit) required @endunless
               onchange="var f=this.files[0]; if(f){document.getElementById('preview').src=URL.createObjectURL(f); document.getElementById('preview').classList.remove('hidden');}">
        <p class="mt-1 text-xs text-ivory-500">JPG, PNG, atau WebP, maksimal 4 MB. Disarankan rasio 4:3 (mis. 1200 x 900 px).</p>
        <p class="mt-1 text-xs text-songket-400">{{ $err('image') }}</p>

        <label for="image_position" class="mb-1.5 mt-5 block text-sm font-semibold">Fokus foto <span class="font-normal text-ivory-500">(opsional)</span></label>
        <input id="image_position" name="image_position" type="text" class="field" value="{{ $v('image_position', 'center') }}" placeholder="center, atau 80% center">
        <p class="mt-1 text-xs text-ivory-500">Mengatur bagian foto yang dipertahankan saat dipotong.</p>
        <p class="mt-1 text-xs text-songket-400">{{ $err('image_position') }}</p>
      </div>
      <div>
        <p class="mb-1.5 text-sm font-semibold">Pratinjau</p>
        <div class="aspect-[4/3] overflow-hidden rounded-2xl border border-ivory-100/10 bg-ink-950">
          <img id="preview" src="{{ $currentImage }}" alt="" class="h-full w-full object-cover {{ $currentImage ? '' : 'hidden' }}">
        </div>
      </div>
    </div>
  </section>

  {{-- ========== Detail memasak ========== --}}
  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Detail memasak</h2>

    <div class="grid gap-5 sm:grid-cols-4">
      <div>
        <label for="prep_time" class="mb-1.5 block text-sm font-semibold">Persiapan</label>
        <input id="prep_time" name="prep_time" type="text" class="field" value="{{ $v('prep_time') }}" placeholder="30 menit" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('prep_time') }}</p>
      </div>
      <div>
        <label for="cook_time" class="mb-1.5 block text-sm font-semibold">Memasak</label>
        <input id="cook_time" name="cook_time" type="text" class="field" value="{{ $v('cook_time') }}" placeholder="2 jam" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('cook_time') }}</p>
      </div>
      <div>
        <label for="servings" class="mb-1.5 block text-sm font-semibold">Porsi</label>
        <input id="servings" name="servings" type="text" class="field" value="{{ $v('servings') }}" placeholder="4 porsi" required>
        <p class="mt-1 text-xs text-songket-400">{{ $err('servings') }}</p>
      </div>
      <div>
        <label for="difficulty" class="mb-1.5 block text-sm font-semibold">Kesulitan</label>
        <select id="difficulty" name="difficulty" class="field" required>
          @foreach (\App\Models\Recipe::DIFFICULTIES as $d)
            <option value="{{ $d }}" @selected($v('difficulty', 'Mudah') === $d)>{{ $d }}</option>
          @endforeach
        </select>
        <p class="mt-1 text-xs text-songket-400">{{ $err('difficulty') }}</p>
      </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
      <div>
        <label for="main_ingredients" class="mb-1.5 block text-sm font-semibold">Bahan utama <span class="font-normal text-ivory-500">(satu baris satu bahan)</span></label>
        <textarea id="main_ingredients" name="main_ingredients" rows="7" class="field" required>{{ $lines('main_ingredients') }}</textarea>
        <p class="mt-1 text-xs text-songket-400">{{ $err('main_ingredients') }}</p>
      </div>
      <div>
        <label for="spice_blend" class="mb-1.5 block text-sm font-semibold">Bumbu dan rempah <span class="font-normal text-ivory-500">(satu baris satu bumbu)</span></label>
        <textarea id="spice_blend" name="spice_blend" rows="7" class="field" required>{{ $lines('spice_blend') }}</textarea>
        <p class="mt-1 text-xs text-songket-400">{{ $err('spice_blend') }}</p>
      </div>
    </div>

    <div>
      <label for="steps" class="mb-1.5 block text-sm font-semibold">Langkah memasak <span class="font-normal text-ivory-500">(satu baris satu langkah, urut dari awal; tanpa nomor)</span></label>
      <textarea id="steps" name="steps" rows="9" class="field" required>{{ $lines('steps') }}</textarea>
      <p class="mt-1 text-xs text-songket-400">{{ $err('steps') }}</p>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
      <div>
        <label for="chef_tips" class="mb-1.5 block text-sm font-semibold">Rahasia dapur</label>
        <textarea id="chef_tips" name="chef_tips" rows="4" class="field" required>{{ $v('chef_tips') }}</textarea>
        <p class="mt-1 text-xs text-songket-400">{{ $err('chef_tips') }}</p>
      </div>
      <div>
        <label for="cultural_note" class="mb-1.5 block text-sm font-semibold">Catatan adat</label>
        <textarea id="cultural_note" name="cultural_note" rows="4" class="field" required>{{ $v('cultural_note') }}</textarea>
        <p class="mt-1 text-xs text-songket-400">{{ $err('cultural_note') }}</p>
      </div>
    </div>
  </section>

  {{-- ========== Profil rasa ========== --}}
  <section class="space-y-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <h2 class="text-2xl font-semibold">Profil rasa <span class="text-base font-normal text-ivory-500">(0 sampai 100)</span></h2>
    <div class="grid gap-5 sm:grid-cols-4">
      @foreach (['spicy' => 'Pedas', 'savory' => 'Gurih', 'sweet' => 'Manis', 'aromatic' => 'Aroma rempah'] as $key => $label)
        <div>
          <label for="{{ $key }}" class="mb-1.5 block text-sm font-semibold">{{ $label }}</label>
          <input id="{{ $key }}" name="{{ $key }}" type="number" min="0" max="100" class="field" value="{{ $v($key, 50) }}" required>
          <p class="mt-1 text-xs text-songket-400">{{ $err($key) }}</p>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ========== Penayangan ========== --}}
  <section class="flex flex-wrap items-center justify-between gap-6 rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
    <div class="flex flex-wrap items-center gap-8">
      <label class="inline-flex items-center gap-3 text-sm font-semibold">
        <input type="checkbox" name="is_published" value="1" class="h-5 w-5 accent-[#e6b94f]" @checked(old('is_published', $recipe?->is_published ?? true))>
        Tayangkan di situs
      </label>
      <div class="flex items-center gap-3">
        <label for="sort_order" class="text-sm font-semibold">Urutan</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="field !w-24" value="{{ $v('sort_order', 0) }}">
      </div>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('admin.recipes.index') }}" class="btn btn-line">Batal</a>
      <button type="submit" class="btn btn-gold">{{ $isEdit ? 'Simpan perubahan' : 'Tambahkan kuliner' }}</button>
    </div>
  </section>
</form>
@endsection
