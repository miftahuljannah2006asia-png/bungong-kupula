{{-- Butuh: $districts (daftar), $recipes (keyed by slug). Interaktif dengan Alpine. --}}
<div x-data="{ active: '{{ $districts[0]['slug'] }}' }" class="grid gap-8 lg:grid-cols-12">

  <div class="lg:col-span-4">
    <div class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto px-4 pb-2 lg:mx-0 lg:flex-col lg:overflow-visible lg:px-0 lg:pb-0" role="tablist" aria-label="Daftar kawasan Pidie">
      @foreach ($districts as $d)
        <button type="button" role="tab"
                @click="active = '{{ $d['slug'] }}'"
                :aria-selected="active === '{{ $d['slug'] }}'"
                :class="active === '{{ $d['slug'] }}' ? 'border-gold-400 bg-gold-400/10' : 'border-ivory-100/10 hover:border-ivory-100/30'"
                class="shrink-0 rounded-xl border px-4 py-3 text-left transition-colors lg:w-full">
          <span class="block font-serif text-lg font-semibold text-ivory-50">{{ $d['name'] }}</span>
          <span class="block text-xs text-ivory-500">{{ $d['geo_type'] }}</span>
        </button>
      @endforeach
    </div>
  </div>

  <div class="lg:col-span-8">
    @foreach ($districts as $d)
      <article x-show="active === '{{ $d['slug'] }}'" @if (! $loop->first) x-cloak @endif
               x-transition.opacity.duration.300ms role="tabpanel"
               class="overflow-hidden rounded-3xl border border-ivory-100/10 bg-ink-900">
        <div class="relative aspect-[16/8] overflow-hidden">
          <img src="{{ asset('images/'.$d['image']) }}" alt="" loading="lazy" class="h-full w-full object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-ink-900 via-ink-900/30 to-transparent"></div>
          <div class="absolute bottom-0 p-6 sm:p-8">
            <p class="text-sm font-semibold text-gold-300">{{ $d['geo_type'] }}</p>
            <h3 class="mt-1 text-3xl font-semibold sm:text-4xl">{{ $d['name'] }}</h3>
          </div>
        </div>

        <div class="grid gap-8 p-6 sm:grid-cols-2 sm:p-8">
          <div>
            <p class="text-ivory-300">{{ $d['description'] }}</p>
            <p class="mt-6 text-sm text-ivory-500">Sajian khas</p>
            <p class="mt-1 font-serif text-xl font-semibold text-ivory-50">{{ $d['highlight_dish'] }}</p>
            @if (! empty($d['recipe']) && isset($recipes[$d['recipe']]))
              <a href="{{ route('recipes.show', $d['recipe']) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-gold-300 hover:text-gold-400">
                Baca resep {{ $recipes[$d['recipe']]['short_name'] }} <x-icon name="arrow-right" class="h-4 w-4" />
              </a>
            @endif
          </div>

          <div>
            <p class="text-sm text-ivory-500">Tempat yang layak dikunjungi</p>
            <ul class="mt-3 space-y-2">
              @foreach ($d['landmarks'] as $landmark)
                <li>
                  <a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($landmark.' Pidie Aceh') }}" target="_blank" rel="noopener"
                     class="group inline-flex items-center gap-2 text-ivory-100 hover:text-gold-300">
                    <x-icon name="pin" class="h-4 w-4 shrink-0 text-gold-400" />
                    <span>{{ $landmark }}</span>
                    <x-icon name="external" class="h-3.5 w-3.5 text-ivory-500 group-hover:text-gold-300" />
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </article>
    @endforeach
  </div>
</div>
