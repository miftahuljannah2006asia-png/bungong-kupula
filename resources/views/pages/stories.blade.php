@extends('layouts.app')

@section('title', 'Hikayat & Budaya Pidie | Bungong Kupula')
@section('description', 'Falsafah bunga kupula, adat peumulia jamee, dan kejayaan Bandar Pedir abad ke-15.')

@section('content')
<section class="mx-auto max-w-7xl px-4 pb-24 pt-14 sm:px-6 lg:px-8 lg:pt-20">
  <h1 class="max-w-3xl text-5xl font-semibold leading-tight sm:text-6xl">Hikayat dan budaya</h1>
  <p class="mt-5 max-w-2xl text-lg text-ivory-300">Cerita di balik rasa: bunga yang menjadi lambang martabat, adat menjamu tamu, dan bandar yang pernah menjadi pintu rempah dunia.</p>

  <div class="mt-14 grid gap-8 lg:grid-cols-3">
    @foreach ($stories as $s)
      <a href="{{ route('stories.show', $s['slug']) }}" class="group flex flex-col overflow-hidden rounded-3xl border border-ivory-100/10 bg-ink-900 transition-colors hover:border-gold-400/40">
        <img src="{{ asset('images/'.$s['image']) }}" alt="" loading="lazy" class="aspect-[16/10] w-full object-cover">
        <div class="flex flex-1 flex-col p-7">
          <p class="text-sm font-semibold text-gold-400">{{ $s['theme'] }}</p>
          <h2 class="mt-2 text-2xl font-semibold leading-snug group-hover:text-gold-300">{{ $s['title'] }}</h2>
          <p class="mt-3 line-clamp-3 text-sm text-ivory-300">{{ $s['excerpt'] }}</p>
          <p class="mt-auto pt-6 text-xs text-ivory-500">{{ $s['author'] }} &middot; {{ $s['read_time'] }}</p>
        </div>
      </a>
    @endforeach
  </div>
</section>
@endsection
