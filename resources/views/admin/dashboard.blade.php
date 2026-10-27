@extends('layouts.admin')

@section('title', 'Ringkasan')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="text-4xl font-semibold">Ringkasan</h1>
    <p class="mt-2 text-ivory-300">Selamat datang kembali. Mulai dari menambah kuliner baru.</p>
  </div>
  <a href="{{ route('admin.recipes.create') }}" class="btn btn-gold">+ Tambah kuliner baru</a>
</div>

@if ($pendingImport > 0)
  <div class="mt-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-gold-400/30 bg-gold-400/5 px-6 py-5">
    <p class="max-w-2xl text-sm text-ivory-100">Ada <strong>{{ $pendingImport }}</strong> resep bawaan situs yang belum bisa diubah lewat admin. Impor sekali saja agar semuanya bisa diedit, disembunyikan, atau diberi foto baru.</p>
    <form method="POST" action="{{ route('admin.recipes.import') }}">
      @csrf
      <button type="submit" class="btn btn-line">Impor resep bawaan</button>
    </form>
  </div>
@endif

<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @foreach ([
    ['Kuliner di database', $recipeTotal, $recipePublished.' tayang'],
    ['Produk oleh-oleh', $productTotal, $productActive.' aktif'],
    ['Pesanan', $orderTotal, $orderNew.' baru'],
    ['Pesan masuk', $messageTotal, 'dari formulir kontak'],
  ] as [$label, $value, $hint])
    <div class="rounded-2xl border border-ivory-100/10 bg-ink-900 p-6">
      <p class="text-sm text-ivory-500">{{ $label }}</p>
      <p class="mt-2 font-serif text-4xl font-semibold text-gold-300">{{ $value }}</p>
      <p class="mt-1 text-xs text-ivory-500">{{ $hint }}</p>
    </div>
  @endforeach
  <a href="{{ route('admin.recipes.index') }}" class="flex flex-col justify-between rounded-2xl border border-gold-400/30 bg-gold-400/5 p-6 transition-colors hover:bg-gold-400/10">
    <p class="text-sm text-ivory-300">Kelola</p>
    <p class="font-serif text-2xl font-semibold text-ivory-50">Daftar kuliner</p>
  </a>
  <a href="{{ route('admin.products.index') }}" class="flex flex-col justify-between rounded-2xl border border-gold-400/30 bg-gold-400/5 p-6 transition-colors hover:bg-gold-400/10">
    <p class="text-sm text-ivory-300">Kelola</p>
    <p class="font-serif text-2xl font-semibold text-ivory-50">Produk oleh-oleh</p>
  </a>
</div>

<section class="mt-12">
  <h2 class="text-2xl font-semibold">Pesanan terbaru</h2>
  <div class="mt-4 overflow-x-auto rounded-2xl border border-ivory-100/10 bg-ink-900">
    <table class="w-full text-left text-sm">
      <thead class="border-b border-ivory-100/10 text-ivory-500">
        <tr><th class="px-5 py-3 font-medium">Kode</th><th class="px-5 py-3 font-medium">Nama</th><th class="px-5 py-3 font-medium">Kota</th><th class="px-5 py-3 font-medium">Total</th><th class="px-5 py-3 font-medium">Waktu</th></tr>
      </thead>
      <tbody class="divide-y divide-ivory-100/10">
        @forelse ($latestOrders as $o)
          <tr>
            <td class="px-5 py-3 font-semibold text-gold-300">{{ $o->code }}</td>
            <td class="px-5 py-3">{{ $o->name }}</td>
            <td class="px-5 py-3 text-ivory-300">{{ $o->city }}</td>
            <td class="px-5 py-3">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
            <td class="px-5 py-3 text-ivory-500">{{ $o->created_at->format('d M Y H:i') }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-5 py-8 text-center text-ivory-500">Belum ada pesanan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
