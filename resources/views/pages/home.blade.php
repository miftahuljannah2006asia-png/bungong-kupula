@extends('layouts.app')

@section('content')

{{-- ========================= HERO ========================= --}}
<section class="relative isolate overflow-hidden">
  <div class="hero-glow absolute inset-0 -z-10" aria-hidden="true"></div>

  <div class="mx-auto grid max-w-7xl items-center gap-14 px-4 pb-20 pt-12 sm:px-6 lg:grid-cols-12 lg:gap-10 lg:px-8 lg:pb-28 lg:pt-20">
    <div class="lg:col-span-7">
      <p class="hero-rise inline-flex items-center gap-2 text-sm font-semibold text-gold-400">
        <x-kupula-mark class="h-5 w-5" /> Portal gastronomi dan budaya Kabupaten Pidie
      </p>

      <h1 class="hero-rise mt-5 text-[2.6rem] font-semibold leading-[1.05] sm:text-6xl lg:text-[4.5rem]">
        Semerbak warisan rasa dari Tanah Meutuwah
      </h1>

      <blockquote class="hero-rise mt-8 max-w-xl border-l-2 border-gold-400/70 pl-5">
        <p class="font-serif text-xl italic text-gold-300">&ldquo;Harom Meuseubap, Megah Meunanggroe&rdquo;</p>
        <p class="mt-1 text-sm text-ivory-500">Seperti bungong kupula yang tetap harum melintasi waktu, begitu pula martabat dan kuliner leluhur Pidie kami rawat.</p>
      </blockquote>

      <p class="hero-rise mt-6 max-w-xl text-lg text-ivory-300">
        Bungong Kupula mendokumentasikan resep pusaka Kuah Beulangong, tempaan emping Beureunuen, jaring kue keukarah,
        kopi dingin lereng Tangse, serta sejarah Kerajaan Pedir, lalu menghubungkan Anda langsung dengan para perajinnya.
      </p>

      <div class="hero-rise mt-9 flex flex-wrap items-center gap-3">
        <a href="{{ route('recipes.index') }}" class="btn btn-gold">Jelajahi pustaka rasa <x-icon name="arrow-right" class="h-4 w-4" /></a>
        <a href="{{ route('market') }}" class="btn btn-line"><x-icon name="bag" class="h-4 w-4 text-gold-400" /> Pesan oleh-oleh asli</a>
        <a href="{{ route('stories.index') }}" class="btn !px-3 text-ivory-300 hover:text-gold-300"><x-icon name="book" class="h-4 w-4 text-gold-400" /> Baca hikayat</a>
      </div>
    </div>

    <figure class="hero-rise relative mx-auto w-full max-w-md lg:col-span-5 lg:max-w-none" style="animation-delay: .2s">
      <div class="arch absolute -inset-3 border border-gold-400/40" aria-hidden="true"></div>
      <div class="arch relative aspect-[4/5] bg-ink-800">
        <img src="{{ asset('images/hero-kupula.jpg') }}" width="1376" height="768" fetchpriority="high"
             alt="Bunga kupula, secangkir kopi kuningan, dan emping dalam bakul di atas papan kayu berukir Aceh"
             class="h-full w-full object-cover" style="object-position: 42% center">
      </div>
    </figure>
  </div>
</section>

{{-- ============== Pita hidangan ============== --}}
<div class="border-y border-ivory-100/10 bg-ink-900/60">
  <div class="no-scrollbar mx-auto flex max-w-7xl items-center gap-7 overflow-x-auto px-4 py-4 sm:px-6 lg:px-8">
    @foreach ($allRecipes as $r)
      <a href="{{ route('recipes.show', $r['slug']) }}" class="shrink-0 whitespace-nowrap font-serif text-lg italic text-ivory-300 hover:text-gold-300">{{ $r['short_name'] }}</a>
      @if (! $loop->last)
        <x-kupula-mark class="h-3.5 w-3.5 shrink-0 text-gold-500/60" />
      @endif
    @endforeach
  </div>
</div>

{{-- ========================= FALSAFAH ========================= --}}
@php $falsafah = $stories[0]; @endphp
<section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
  <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="relative grid place-items-center lg:col-span-5">
      <div class="absolute inset-0 rounded-full bg-gold-400/5 blur-3xl" aria-hidden="true"></div>
      <x-kupula-mark class="relative h-56 w-56 text-gold-400 sm:h-72 sm:w-72" />
    </div>

    <div class="lg:col-span-7">
      <h2 class="text-4xl font-semibold leading-tight sm:text-5xl">{{ $falsafah['title'] }}</h2>
      <p class="mt-6 max-w-2xl text-lg text-ivory-300">{{ $falsafah['excerpt'] }}</p>

      <figure class="mt-8 border-l-2 border-gold-400/70 pl-5">
        <blockquote class="font-serif text-2xl italic leading-snug text-gold-300">&ldquo;{{ $falsafah['quote_aceh'] }}&rdquo;</blockquote>
        <figcaption class="mt-2 text-sm text-ivory-500">{{ $falsafah['quote_indo'] }}</figcaption>
      </figure>

      <a href="{{ route('stories.show', $falsafah['slug']) }}" class="mt-8 inline-flex items-center gap-2 font-semibold text-gold-300 hover:text-gold-400">
        Baca kisah lengkapnya <x-icon name="arrow-right" class="h-4 w-4" />
      </a>
    </div>
  </div>
</section>

<div class="motif-band" aria-hidden="true"></div>

{{-- ========================= PUSTAKA RASA ========================= --}}
@php
    $featured = reset($recipes);
    $rest = array_slice($recipes, 1);
@endphp
<section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
  <div class="flex flex-wrap items-end justify-between gap-5">
    <div>
      <h2 class="text-4xl font-semibold sm:text-5xl">Pustaka rasa</h2>
      <p class="mt-3 max-w-xl text-ivory-300">Teknik memasak asli, takaran bumbu leluhur, dan adat penyajiannya, ditulis sampai ke rahasia kecil dapur Pidie.</p>
    </div>
    <a href="{{ route('recipes.index') }}" class="btn btn-line">Lihat semua resep</a>
  </div>

  <div class="mt-12 grid gap-5 lg:grid-cols-5">
    <a href="{{ route('recipes.show', $featured['slug']) }}"
       class="group relative isolate flex min-h-[26rem] flex-col justify-end overflow-hidden rounded-3xl border border-ivory-100/10 lg:col-span-3 lg:row-span-2">
      <img src="{{ $featured['image_url'] }}" alt="" loading="lazy" width="1200" height="896"
           class="absolute inset-0 -z-10 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
           style="object-position: {{ $featured['image_position'] }}">
      <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950 via-ink-950/50 to-transparent"></div>
      <div class="p-7 sm:p-10">
        <span class="rounded-full bg-ink-950/80 px-3 py-1 text-xs font-semibold text-gold-300 backdrop-blur">{{ $featured['category'] }}</span>
        <h3 class="mt-4 text-3xl font-semibold leading-tight sm:text-4xl">{{ $featured['name'] }}</h3>
        <p class="mt-3 max-w-lg text-ivory-300">{{ $featured['short_desc'] }}</p>
        <p class="mt-5 inline-flex items-center gap-4 text-sm text-ivory-300">
          <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="h-4 w-4 text-gold-400" /> {{ $featured['cook_time'] }}</span>
          <span class="inline-flex items-center gap-1.5"><x-icon name="users" class="h-4 w-4 text-gold-400" /> {{ $featured['servings'] }}</span>
        </p>
      </div>
    </a>

    @foreach ($rest as $r)
      <a href="{{ route('recipes.show', $r['slug']) }}"
         class="group flex gap-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-4 transition-colors hover:border-gold-400/40 lg:col-span-2">
        <img src="{{ $r['image_url'] }}" alt="" loading="lazy" width="1200" height="896"
             class="h-32 w-32 shrink-0 rounded-xl object-cover sm:h-40 sm:w-40" style="object-position: {{ $r['image_position'] }}">
        <div class="flex min-w-0 flex-col justify-center">
          <span class="text-xs font-semibold text-gold-400">{{ $r['category'] }}</span>
          <h3 class="mt-1 text-xl font-semibold leading-snug group-hover:text-gold-300">{{ $r['name'] }}</h3>
          <p class="mt-2 line-clamp-2 text-sm text-ivory-300">{{ $r['short_desc'] }}</p>
        </div>
      </a>
    @endforeach
  </div>
</section>

{{-- ========================= PETA WILAYAH ========================= --}}
<section class="border-y border-ivory-100/10 bg-ink-900/50">
  <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
      <h2 class="text-4xl font-semibold sm:text-5xl">Enam kawasan, enam cara memuliakan rasa</h2>
      <p class="mt-3 text-ivory-300">Dari pesisir Selat Malaka hingga lembah berkabut Bukit Barisan, setiap kawasan Pidie menyumbang satu bahan dan satu cerita.</p>
    </div>
    <div class="mt-12">
      @include('partials.region-explorer', ['districts' => $districts, 'recipes' => $allRecipes])
    </div>
  </div>
</section>

{{-- ========================= PASAR OLEH-OLEH ========================= --}}
<section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
  <div class="flex flex-wrap items-end justify-between gap-5">
    <div>
      <h2 class="text-4xl font-semibold sm:text-5xl">Pasar oleh-oleh</h2>
      <p class="mt-3 max-w-xl text-ivory-300">Produk dibuat dan dikirim langsung oleh perajin Pidie. Harga mengikuti daftar terbaru dari perajin.</p>
    </div>
    <a href="{{ route('market') }}" class="btn btn-line">Lihat semua produk</a>
  </div>

  <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($products as $product)
      <x-product-card :product="$product" />
    @endforeach
  </div>
</section>

{{-- ========================= HIKAYAT ========================= --}}
<section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
  <div class="max-w-2xl">
    <h2 class="text-4xl font-semibold sm:text-5xl">Hikayat dan budaya</h2>
    <p class="mt-3 text-ivory-300">Catatan tentang falsafah bunga, adat menjamu tamu, dan kejayaan Bandar Pedir.</p>
  </div>

  <div class="mt-10 divide-y divide-ivory-100/10 border-y border-ivory-100/10">
    @foreach ($stories as $s)
      <a href="{{ route('stories.show', $s['slug']) }}" class="group grid items-center gap-6 py-8 md:grid-cols-12">
        <img src="{{ asset('images/'.$s['image']) }}" alt="" loading="lazy" class="aspect-[16/10] w-full rounded-2xl object-cover md:col-span-3">
        <div class="md:col-span-7">
          <p class="text-sm font-semibold text-gold-400">{{ $s['theme'] }} &middot; {{ $s['read_time'] }}</p>
          <h3 class="mt-2 text-2xl font-semibold leading-snug group-hover:text-gold-300">{{ $s['title'] }}</h3>
          <p class="mt-2 line-clamp-2 text-ivory-300">{{ $s['excerpt'] }}</p>
        </div>
        <div class="hidden justify-end md:col-span-2 md:flex">
          <span class="grid h-11 w-11 place-items-center rounded-full border border-ivory-100/20 text-ivory-100 transition-colors group-hover:border-gold-400 group-hover:text-gold-300"><x-icon name="arrow-right" /></span>
        </div>
      </a>
    @endforeach
  </div>
</section>

{{-- ========================= AJAKAN ========================= --}}
<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
  <div class="relative isolate overflow-hidden rounded-3xl border border-gold-400/30 bg-ink-800 px-6 py-14 text-center sm:px-14">
    <div class="hero-glow absolute inset-0 -z-10" aria-hidden="true"></div>
    <x-kupula-mark class="mx-auto h-10 w-10 text-gold-400" />
    <h2 class="mx-auto mt-5 max-w-2xl text-3xl font-semibold sm:text-4xl">Punya resep keluarga atau kisah dari kampung?</h2>
    <p class="mx-auto mt-3 max-w-xl text-ivory-300">Kirimkan kepada kami. Setiap resep yang ditulis hari ini adalah warisan bagi dapur esok hari.</p>
    <a href="{{ route('contact') }}" class="btn btn-gold mt-8">Kirim resep atau kisah</a>
  </div>
</section>

@endsection
