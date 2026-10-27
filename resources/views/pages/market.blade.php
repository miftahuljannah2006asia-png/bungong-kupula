@extends('layouts.app')

@section('title', 'Pasar Oleh-Oleh Pidie | Bungong Kupula')
@section('description', 'Emping melinjo Beureunuen, kopi robusta Tangse, kue keukarah, dan asam sunti langsung dari perajin Pidie.')

@section('content')
<section class="mx-auto max-w-7xl px-4 pb-10 pt-14 sm:px-6 lg:px-8 lg:pt-20">
  <h1 class="max-w-3xl text-5xl font-semibold leading-tight sm:text-6xl">Pasar oleh-oleh</h1>
  <p class="mt-5 max-w-2xl text-lg text-ivory-300">Dibuat dan dikirim langsung oleh perajin Pidie. Pilih produk, isi data pengiriman, lalu konfirmasi lewat WhatsApp.</p>

  <ol class="mt-10 grid gap-4 sm:grid-cols-3">
    @foreach (['Pilih produk dan masukkan ke keranjang', 'Isi nama dan alamat pengiriman', 'Konfirmasi ongkir dan pembayaran lewat WhatsApp'] as $step)
      <li class="flex items-center gap-4 rounded-2xl border border-ivory-100/10 bg-ink-900 p-4">
        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gold-400 font-serif text-lg font-bold text-ink-950">{{ $loop->iteration }}</span>
        <span class="text-sm text-ivory-300">{{ $step }}</span>
      </li>
    @endforeach
  </ol>
</section>

<section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
  <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($products as $product)
      <x-product-card :product="$product" />
    @empty
      <p class="col-span-full py-16 text-center text-ivory-300">Produk sedang disiapkan perajin. Silakan kembali lagi nanti.</p>
    @endforelse
  </div>
</section>
@endsection
