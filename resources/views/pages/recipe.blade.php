@extends('layouts.app')

@section('title', $recipe['name'].' | Bungong Kupula')
@section('description', $recipe['short_desc'])
@section('og_image', $recipe['image_url'])

@section('content')
<article>
  <header class="relative isolate overflow-hidden">
    <img src="{{ $recipe['image_url'] }}" alt="" width="1200" height="896" fetchpriority="high"
         class="absolute inset-0 -z-20 h-full w-full object-cover opacity-45" style="object-position: {{ $recipe['image_position'] }}">
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950 via-ink-950/70 to-ink-950/30"></div>

    <div class="mx-auto max-w-7xl px-4 pb-12 pt-10 sm:px-6 lg:px-8 lg:pb-16 lg:pt-14">
      <a href="{{ route('recipes.index') }}" class="inline-flex items-center gap-2 text-sm text-ivory-300 hover:text-gold-300"><x-icon name="arrow-left" class="h-4 w-4" /> Pustaka rasa</a>

      <p class="mt-16 text-sm font-semibold text-gold-300 sm:mt-24">{{ $recipe['category'] }} &middot; {{ $recipe['region'] }}</p>
      <h1 class="mt-3 max-w-4xl text-4xl font-semibold leading-[1.08] sm:text-6xl">{{ $recipe['name'] }}</h1>
      <p class="mt-5 max-w-2xl text-lg text-ivory-300">{{ $recipe['short_desc'] }}</p>

      <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-3 text-sm text-ivory-100">
        <span class="inline-flex items-center gap-2"><x-icon name="clock" class="h-4 w-4 text-gold-400" /> Persiapan {{ $recipe['prep_time'] }}</span>
        <span class="inline-flex items-center gap-2"><x-icon name="flame" class="h-4 w-4 text-gold-400" /> Memasak {{ $recipe['cook_time'] }}</span>
        <span class="inline-flex items-center gap-2"><x-icon name="users" class="h-4 w-4 text-gold-400" /> {{ $recipe['servings'] }}</span>
        <span class="inline-flex items-center gap-2"><x-icon name="chef" class="h-4 w-4 text-gold-400" /> {{ $recipe['difficulty'] }}</span>
      </div>

      <button type="button" class="btn btn-line group/save mt-8" @click="$store.kupula.toggleSave('{{ $recipe['slug'] }}')" :aria-pressed="$store.kupula.isSaved('{{ $recipe['slug'] }}')">
        <x-icon name="heart" class="h-4 w-4 group-aria-pressed/save:fill-songket-400 group-aria-pressed/save:text-songket-400" />
        <span x-text="$store.kupula.isSaved('{{ $recipe['slug'] }}') ? 'Tersimpan' : 'Simpan resep'"></span>
      </button>
    </div>
  </header>

  <div class="mx-auto grid max-w-7xl gap-12 px-4 py-14 sm:px-6 lg:grid-cols-12 lg:gap-16 lg:px-8">

    <aside class="space-y-8 lg:col-span-4">
      <div class="rounded-2xl border border-ivory-100/10 bg-ink-900 p-6 lg:sticky lg:top-24">
        <h2 class="text-2xl font-semibold">Bahan utama</h2>
        <ul class="mt-4 space-y-2.5 text-sm text-ivory-300">
          @foreach ($recipe['main_ingredients'] as $item)
            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" /> {{ $item }}</li>
          @endforeach
        </ul>

        <h2 class="mt-8 text-2xl font-semibold">Bumbu dan rempah</h2>
        <ul class="mt-4 space-y-2.5 text-sm text-ivory-300">
          @foreach ($recipe['spice_blend'] as $item)
            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-songket-400" /> {{ $item }}</li>
          @endforeach
        </ul>

        <h2 class="mt-8 text-2xl font-semibold">Profil rasa</h2>
        <div class="mt-4 space-y-3 text-xs text-ivory-300">
          @foreach (['spicy' => ['Pedas', 'bg-songket-400'], 'savory' => ['Gurih', 'bg-gold-400'], 'sweet' => ['Manis', 'bg-gold-300'], 'aromatic' => ['Aroma rempah', 'bg-leaf-400']] as $key => [$label, $color])
            <div>
              <div class="flex justify-between"><span>{{ $label }}</span><span>{{ $recipe['flavor_profile'][$key] }}%</span></div>
              <div class="mt-1 h-1.5 rounded-full bg-ink-700"><div class="h-full rounded-full {{ $color }}" style="width: {{ $recipe['flavor_profile'][$key] }}%"></div></div>
            </div>
          @endforeach
        </div>
      </div>
    </aside>

    <div class="lg:col-span-8">
      <h2 class="text-3xl font-semibold">Kisah di balik hidangan</h2>
      <div class="prose-kupula mt-5"><p>{{ $recipe['full_story'] }}</p></div>

      <h2 class="mt-14 text-3xl font-semibold">Cara memasak</h2>
      <ol class="mt-6 space-y-6">
        @foreach ($recipe['steps'] as $step)
          <li class="flex gap-5">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gold-400 font-serif text-lg font-bold text-ink-950">{{ $loop->iteration }}</span>
            <p class="pt-1 text-ivory-300">{{ $step }}</p>
          </li>
        @endforeach
      </ol>

      <div class="mt-14 grid gap-5 sm:grid-cols-2">
        <div class="rounded-2xl border border-gold-400/30 bg-gold-400/5 p-6">
          <h3 class="text-xl font-semibold text-gold-300">Rahasia dapur</h3>
          <p class="mt-3 text-sm text-ivory-300">{{ $recipe['chef_tips'] }}</p>
        </div>
        <div class="rounded-2xl border border-ivory-100/10 bg-ink-900 p-6">
          <h3 class="text-xl font-semibold">Catatan adat</h3>
          <p class="mt-3 text-sm text-ivory-300">{{ $recipe['cultural_note'] }}</p>
        </div>
      </div>
    </div>
  </div>

  <section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-semibold">Resep lainnya</h2>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
      @foreach ($related as $r)
        <x-recipe-card :recipe="$r" />
      @endforeach
    </div>
  </section>
</article>
@endsection
