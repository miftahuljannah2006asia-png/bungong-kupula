@extends('layouts.app')

@section('title', 'Peta Wilayah Pidie | Bungong Kupula')
@section('description', 'Enam kawasan ikonik Kabupaten Pidie: Sigli, Beureunuen, Tangse, Grong-Grong, Keumala, dan Geumpang, beserta sajian khas dan tempat yang layak dikunjungi.')

@section('content')
<section class="mx-auto max-w-7xl px-4 pb-24 pt-14 sm:px-6 lg:px-8 lg:pt-20">
  <h1 class="max-w-3xl text-5xl font-semibold leading-tight sm:text-6xl">Peta wilayah Pidie</h1>
  <p class="mt-5 max-w-2xl text-lg text-ivory-300">Pilih satu kawasan untuk melihat sajian khasnya, tempat yang layak dikunjungi, dan resep yang berkaitan.</p>

  <div class="mt-12">
    @include('partials.region-explorer', ['districts' => $districts, 'recipes' => $recipes])
  </div>
</section>
@endsection
