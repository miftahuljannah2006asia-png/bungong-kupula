@props(['product'])
@php
    $cartData = [
        'id' => $product['id'],
        'title' => $product['title'],
        'price' => $product['price'],
        'unit' => $product['unit'],
        'artisan' => $product['artisan'],
        'image' => $product['image_url'],
    ];
@endphp
<article class="flex h-full flex-col overflow-hidden rounded-2xl border border-ivory-100/10 bg-ink-900">
  <div class="relative aspect-[4/3] overflow-hidden">
    <img src="{{ $product['image_url'] }}" alt="" loading="lazy" width="1200" height="896"
         class="h-full w-full object-cover" style="object-position: {{ $product['image_position'] }}">
    <div class="absolute inset-0 bg-gradient-to-t from-ink-900 via-transparent to-transparent"></div>
    @if (filled($product['badge']))
      <span class="absolute left-3 top-3 rounded-full bg-gold-400 px-3 py-1 text-xs font-bold text-ink-950">{{ $product['badge'] }}</span>
    @endif
  </div>

  <div class="flex flex-1 flex-col p-5">
    <h3 class="text-lg font-semibold leading-snug">{{ $product['title'] }}</h3>
    <p class="mt-1.5 text-xs text-ivory-500">
      {{ $product['artisan'] }}
      @if (filled($product['village'])) &middot; {{ $product['village'] }} @endif
    </p>
    <p class="mt-3 line-clamp-3 text-sm text-ivory-300">{{ $product['description'] }}</p>

    @if (count($product['features']))
      <ul class="mt-3 space-y-1 text-xs text-ivory-300">
        @foreach (array_slice($product['features'], 0, 3) as $feature)
          <li class="flex items-start gap-2"><x-icon name="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-leaf-400" /> {{ $feature }}</li>
        @endforeach
      </ul>
    @endif

    <div class="mt-auto pt-5">
      @if ($product['rating'] > 0)
        <p class="mb-3 flex items-center gap-1.5 text-xs text-ivory-300">
          <x-icon name="star" class="h-3.5 w-3.5 fill-gold-400 text-gold-400" />
          <span class="font-semibold text-ivory-50">{{ number_format($product['rating'], 1, ',', '.') }}</span>
          @if ($product['reviews'] > 0) <span class="text-ivory-500">({{ $product['reviews'] }} ulasan)</span> @endif
        </p>
      @endif

      <div class="flex items-end justify-between gap-3">
        <div>
          <p class="font-serif text-xl font-semibold text-gold-300">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
          <p class="max-w-[11rem] text-xs leading-snug text-ivory-500">{{ $product['unit'] }}</p>
        </div>
        <button type="button" class="btn btn-gold shrink-0 !px-4 !py-2.5" @click="$store.kupula.add(@js($cartData))"
                aria-label="Tambah {{ $product['title'] }} ke keranjang">
          <x-icon name="plus" class="h-4 w-4" /> Keranjang
        </button>
      </div>
    </div>
  </div>
</article>
