@php
    $nav = [
        ['Beranda', 'home', 'home'],
        ['Pustaka rasa', 'recipes.index', 'recipes.*'],
        ['Peta wilayah', 'regions', 'regions'],
        ['Pasar oleh-oleh', 'market', 'market'],
        ['Hikayat', 'stories.index', 'stories.*'],
        ['Kontak', 'contact', 'contact'],
    ];
@endphp
<header class="sticky top-0 z-40 border-b border-ivory-100/10 bg-ink-950/90 backdrop-blur-md" x-data="{ open: false }" @keydown.escape.window="open = false">
  <div class="mx-auto flex h-[4.5rem] max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

    <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="Bungong Kupula - ke beranda">
      <x-kupula-mark class="h-10 w-10 text-gold-400 transition-transform duration-500 group-hover:rotate-45" />
      <span class="leading-none">
        <span class="block font-serif text-[1.3rem] font-semibold text-ivory-50">Bungong Kupula</span>
        <span class="mt-1 block text-[0.72rem] text-ivory-500">Gastronomi &amp; budaya Pidie</span>
      </span>
    </a>

    <nav class="hidden items-center gap-7 lg:flex" aria-label="Navigasi utama">
      @foreach ($nav as [$label, $route, $pattern])
        @php $active = request()->routeIs($pattern); @endphp
        <a href="{{ route($route) }}"
           @if ($active) aria-current="page" @endif
           class="relative py-1 text-sm font-medium transition-colors hover:text-gold-300 {{ $active ? 'text-gold-300' : 'text-ivory-300' }}">
          {{ $label }}
          @if ($active)<span class="absolute inset-x-0 -bottom-1 h-px bg-gold-400"></span>@endif
        </a>
      @endforeach
    </nav>

    <div class="flex items-center gap-2">
      <button type="button" @click="$store.kupula.savedOpen = true"
              class="relative grid h-10 w-10 place-items-center rounded-full border border-ivory-100/15 text-ivory-100 transition-colors hover:border-gold-400 hover:text-gold-300"
              aria-label="Buka resep tersimpan">
        <x-icon name="heart" class="h-[1.15rem] w-[1.15rem]" />
        <span x-show="$store.kupula.saved.length > 0" x-cloak x-text="$store.kupula.saved.length"
              class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-songket-500 px-1 text-[0.65rem] font-bold text-ivory-50"></span>
      </button>

      <button type="button" @click="$store.kupula.cartOpen = true"
              class="btn btn-gold relative !px-4 !py-2.5" aria-label="Buka keranjang oleh-oleh">
        <x-icon name="bag" class="h-[1.1rem] w-[1.1rem]" />
        <span class="hidden sm:inline">Keranjang</span>
        <span x-show="$store.kupula.cartCount > 0" x-cloak x-text="$store.kupula.cartCount"
              class="grid h-5 min-w-5 place-items-center rounded-full bg-ink-950 px-1 text-[0.7rem] font-bold text-gold-300"></span>
      </button>

      <button type="button" class="grid h-10 w-10 place-items-center rounded-full text-ivory-100 hover:bg-ivory-100/10 lg:hidden"
              @click="open = !open" :aria-expanded="open" aria-controls="menu-mobile" aria-label="Buka menu">
        <x-icon name="menu" x-show="!open" />
        <x-icon name="x" x-show="open" x-cloak />
      </button>
    </div>
  </div>

  <nav id="menu-mobile" x-show="open" x-cloak x-transition.opacity class="border-t border-ivory-100/10 bg-ink-900 px-4 pb-5 pt-3 lg:hidden" aria-label="Navigasi seluler">
    @foreach ($nav as [$label, $route, $pattern])
      <a href="{{ route($route) }}"
         class="block rounded-lg px-3 py-3 text-base font-medium {{ request()->routeIs($pattern) ? 'bg-gold-400/10 text-gold-300' : 'text-ivory-300 hover:bg-ivory-100/5' }}">{{ $label }}</a>
    @endforeach
  </nav>
</header>
