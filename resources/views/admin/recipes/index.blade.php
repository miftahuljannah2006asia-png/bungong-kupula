@extends('layouts.admin')

@section('title', 'Kuliner')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="text-4xl font-semibold">Kuliner</h1>
    <p class="mt-2 text-ivory-300">Tambah, ubah, atau sembunyikan resep yang tampil di Pustaka Rasa.</p>
  </div>
  <a href="{{ route('admin.recipes.create') }}" class="btn btn-gold">+ Tambah kuliner baru</a>
</div>

@if ($pendingImport > 0)
  <div class="mt-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-gold-400/30 bg-gold-400/5 px-6 py-5">
    <p class="max-w-2xl text-sm text-ivory-100"><strong>{{ $pendingImport }}</strong> resep bawaan belum muncul di daftar ini. Impor agar bisa diubah.</p>
    <form method="POST" action="{{ route('admin.recipes.import') }}">
      @csrf
      <button type="submit" class="btn btn-line">Impor resep bawaan</button>
    </form>
  </div>
@endif

<div class="mt-8 overflow-x-auto rounded-2xl border border-ivory-100/10 bg-ink-900">
  <table class="w-full text-left text-sm">
    <thead class="border-b border-ivory-100/10 text-ivory-500">
      <tr>
        <th class="px-5 py-3 font-medium">Kuliner</th>
        <th class="px-5 py-3 font-medium">Kategori</th>
        <th class="px-5 py-3 font-medium">Status</th>
        <th class="px-5 py-3 text-right font-medium">Tindakan</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ivory-100/10">
      @forelse ($recipes as $r)
        @php
          $isBuiltin = in_array($r->slug, $builtin, true);
          $img = $r->image ? asset(str_starts_with($r->image, 'uploads/') ? $r->image : 'images/'.$r->image) : asset('images/dapur-pusaka.jpg');
        @endphp
        <tr>
          <td class="px-5 py-4">
            <div class="flex items-center gap-4">
              <img src="{{ $img }}" alt="" class="h-14 w-14 shrink-0 rounded-xl object-cover" loading="lazy">
              <div>
                <p class="font-semibold text-ivory-50">{{ $r->name }}</p>
                <p class="text-xs text-ivory-500">{{ $r->region }} @if ($isBuiltin) &middot; bawaan @endif</p>
              </div>
            </div>
          </td>
          <td class="px-5 py-4 text-ivory-300">{{ $r->category }}</td>
          <td class="px-5 py-4">
            @if ($r->is_published)
              <span class="rounded-full bg-leaf-400/15 px-3 py-1 text-xs font-semibold text-leaf-400">Tayang</span>
            @else
              <span class="rounded-full bg-ivory-100/10 px-3 py-1 text-xs font-semibold text-ivory-500">Disembunyikan</span>
            @endif
          </td>
          <td class="px-5 py-4">
            <div class="flex items-center justify-end gap-2">
              @if ($r->is_published)
                <a href="{{ route('recipes.show', $r->slug) }}" target="_blank" class="rounded-lg px-3 py-2 text-ivory-300 hover:text-gold-300">Lihat</a>
              @endif
              <a href="{{ route('admin.recipes.edit', $r) }}" class="rounded-lg border border-ivory-100/15 px-3 py-2 font-semibold hover:border-gold-400 hover:text-gold-300">Ubah</a>
              <form method="POST" action="{{ route('admin.recipes.destroy', $r) }}"
                    onsubmit="return confirm('{{ $isBuiltin ? 'Sembunyikan resep ini dari situs?' : 'Hapus resep ini secara permanen?' }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg px-3 py-2 text-ivory-500 hover:text-songket-400">{{ $isBuiltin ? 'Sembunyikan' : 'Hapus' }}</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="px-5 py-12 text-center text-ivory-500">Belum ada kuliner di database. Klik &ldquo;Tambah kuliner baru&rdquo; atau impor resep bawaan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
