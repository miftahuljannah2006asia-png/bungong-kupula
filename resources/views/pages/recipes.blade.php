@extends('layouts.app')

@section('title', 'Pustaka Rasa - Resep Pusaka Pidie | Bungong Kupula')
@section('description', 'Resep otentik Pidie: Kuah Beulangong, Mie Caluk, emping Beureunuen, keukarah, kopi sanger Tangse, dan Ie Bu Peudah, lengkap dengan bumbu dan langkah memasak.')

@section('content')
<section class="mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8 lg:pt-20">
  <h1 class="max-w-3xl text-5xl font-semibold leading-tight sm:text-6xl">Pustaka rasa</h1>
  <p class="mt-5 max-w-2xl text-lg text-ivory-300">Teknik memasak asli, takaran bumbu leluhur, profil rasa, dan adat penyajian dari seluruh penjuru Pidie.</p>
</section>

<section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8" x-data="recipeFilter(@js($filterIndex))">
  <div class="rounded-2xl border border-ivory-100/10 bg-ink-900 p-4 sm:p-5">
    <div class="flex flex-col gap-3 md:flex-row">
      <div class="relative flex-1">
        <label for="cari" class="sr-only">Cari resep</label>
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ivory-500" />
        <input id="cari" type="search" x-model="q" class="field !pl-10" placeholder="Cari masakan atau bahan: asam sunti, nangka, mie lidi...">
      </div>
      <div>
        <label for="urut" class="sr-only">Urutkan</label>
        <select id="urut" x-model="sort" class="field md:w-60">
          <option value="default">Pilihan kurator</option>
          <option value="spicy">Paling pedas</option>
          <option value="savory">Paling gurih</option>
        </select>
      </div>
    </div>

    <div class="no-scrollbar mt-4 flex gap-2 overflow-x-auto" role="group" aria-label="Filter kategori">
      <button type="button" @click="cat = 'all'" :aria-pressed="cat === 'all'"
              :class="cat === 'all' ? 'bg-gold-400 text-ink-950' : 'text-ivory-300 hover:bg-ivory-100/10'"
              class="shrink-0 rounded-full px-4 py-2 text-sm font-semibold transition-colors">Semua</button>
      @foreach ($categories as $category)
        <button type="button" @click="cat = @js($category)" :aria-pressed="cat === @js($category)"
                :class="cat === @js($category) ? 'bg-gold-400 text-ink-950' : 'text-ivory-300 hover:bg-ivory-100/10'"
                class="shrink-0 rounded-full px-4 py-2 text-sm font-semibold transition-colors">{{ $category }}</button>
      @endforeach
    </div>
  </div>

  <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($recipes as $recipe)
      <div x-show="show('{{ $recipe['slug'] }}')" :style="{ order: order('{{ $recipe['slug'] }}') }">
        <x-recipe-card :recipe="$recipe" />
      </div>
    @endforeach
  </div>

  <div x-show="visible.length === 0" x-cloak class="py-20 text-center">
    <p class="font-serif text-2xl text-ivory-50">Tidak ada resep yang cocok</p>
    <p class="mt-2 text-ivory-300">Coba kata kunci lain, atau tampilkan semua resep.</p>
    <button type="button" class="btn btn-gold mt-6" @click="reset()">Tampilkan semua</button>
  </div>
</section>
@endsection
