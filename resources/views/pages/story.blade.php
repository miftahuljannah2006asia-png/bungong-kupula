@extends('layouts.app')

@section('title', $story['title'].' | Bungong Kupula')
@section('description', $story['excerpt'])
@section('og_image', asset('images/'.$story['image']))

@section('content')
<article>
  <header class="mx-auto max-w-4xl px-4 pt-12 sm:px-6 lg:pt-16">
    <a href="{{ route('stories.index') }}" class="inline-flex items-center gap-2 text-sm text-ivory-300 hover:text-gold-300"><x-icon name="arrow-left" class="h-4 w-4" /> Hikayat dan budaya</a>
    <p class="mt-10 text-sm font-semibold text-gold-400">{{ $story['theme'] }} &middot; {{ $story['read_time'] }}</p>
    <h1 class="mt-3 text-4xl font-semibold leading-[1.1] sm:text-6xl">{{ $story['title'] }}</h1>
    <p class="mt-5 font-serif text-xl italic text-ivory-300">{{ $story['subtitle'] }}</p>
    <p class="mt-5 text-sm text-ivory-500">Oleh {{ $story['author'] }}</p>
  </header>

  <div class="mx-auto mt-10 max-w-5xl px-4 sm:px-6">
    <img src="{{ asset('images/'.$story['image']) }}" alt="{{ $story['cover_image_theme'] }}" class="aspect-[16/8] w-full rounded-3xl object-cover">
  </div>

  <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6">
    <div class="prose-kupula">
      @foreach ($story['paragraphs'] as $p)
        <p>{{ $p }}</p>
      @endforeach
    </div>

    <figure class="mt-12 border-l-2 border-gold-400/70 pl-6">
      <blockquote class="font-serif text-2xl italic leading-snug text-gold-300 sm:text-3xl">&ldquo;{{ $story['quote_aceh'] }}&rdquo;</blockquote>
      <figcaption class="mt-3 text-ivory-500">{{ $story['quote_indo'] }}</figcaption>
    </figure>
  </div>
</article>

<section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
  <h2 class="text-3xl font-semibold">Hikayat lainnya</h2>
  <div class="mt-8 grid gap-6 md:grid-cols-2">
    @foreach ($others as $s)
      <a href="{{ route('stories.show', $s['slug']) }}" class="group flex gap-5 rounded-2xl border border-ivory-100/10 bg-ink-900 p-4 transition-colors hover:border-gold-400/40">
        <img src="{{ asset('images/'.$s['image']) }}" alt="" loading="lazy" class="h-28 w-28 shrink-0 rounded-xl object-cover">
        <div class="flex flex-col justify-center">
          <p class="text-xs font-semibold text-gold-400">{{ $s['theme'] }}</p>
          <h3 class="mt-1 text-lg font-semibold leading-snug group-hover:text-gold-300">{{ $s['title'] }}</h3>
        </div>
      </a>
    @endforeach
  </div>
</section>
@endsection
