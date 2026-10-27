@extends('layouts.app')

@section('title', 'Halaman tidak ditemukan | Bungong Kupula')

@section('content')
<section class="mx-auto flex max-w-3xl flex-col items-center px-4 py-28 text-center sm:px-6">
  <x-kupula-mark class="h-16 w-16 text-ink-600" />
  <h1 class="mt-8 text-4xl font-semibold sm:text-5xl">Halaman ini belum kami temukan</h1>
  <p class="mt-4 text-ivory-300">Alamat yang Anda tuju mungkin sudah berpindah. Mari kembali ke beranda atau jelajahi resep pusaka Pidie.</p>
  <div class="mt-8 flex flex-wrap justify-center gap-3">
    <a href="{{ route('home') }}" class="btn btn-gold">Ke beranda</a>
    <a href="{{ route('recipes.index') }}" class="btn btn-line">Lihat pustaka rasa</a>
  </div>
</section>
@endsection
